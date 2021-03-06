/*
Copyright (c) 2018 by Benjamin (https://codepen.io/maggiben/pen/OPmLBW)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

! function() {

  var today = moment();

  function Calendar(selector, events) {
    this.el = document.querySelector(selector);
    this.events = events;
    this.maxEvents = this.events.reduce(function(p, c){
      if(c.events.length > p) {
        return c.events.length;
      } else {
        return p;
      }
    }, 0);
    this.current = moment().date(1);
    this.draw();
    var current = document.querySelector('.today');
    if (current) {
      var self = this;
      window.setTimeout(function() {
        self.openDay(current);
      }, 500);
    }
  }

  Calendar.prototype.draw = function() {
    //Create Header
    this.drawHeader();
    //Draw Month
    this.drawMonth();
    // Draw Legend
    //this.drawLegend();
  }

  Calendar.prototype.drawHeader = function() {
    var self = this;
    if (!this.header) {
      //Create the header elements
      this.header = createElement('div', 'header');
      this.header.className = 'header';

      //this.title = createElement('h1');
      this.title = {
        month: createElement('div', 'month', this.current.format('MMMM')),
        year: createElement('div', 'year', this.current.format('YYYY'))
      }

      var right = createElement('div', 'right');
      right.addEventListener('click', function() {
        self.nextMonth();
      });

      var left = createElement('div', 'left');
      left.addEventListener('click', function() {
        self.prevMonth();
      });

      var ringLeft = createElement('div', 'ring-left');
      var ringRight = createElement('div', 'ring-right');
      //Append the Elements
      //this.header.appendChild(this.title);
      this.header.appendChild(this.title.month);
      this.header.appendChild(this.title.year);
      this.header.appendChild(ringLeft);
      this.header.appendChild(ringRight);

      this.header.appendChild(right);
      this.header.appendChild(left);
      this.el.appendChild(this.header);
      this.drawWeekDays();
    }

    //this.title.innerHTML = this.current.format('MMMM YYYY');
    this.title.month.innerHTML = this.current.format('MMMM');
    this.title.year.innerHTML = this.current.format('YYYY');
  }

  Calendar.prototype.drawMonth = function() {
    var self = this;

    this.events.forEach(function(event) {
      //ev.date = self.current.clone().date(Math.random() * (29 - 1) + 1);
      event.date = moment(event.date);
    });

    if (this.month) {
      this.oldMonth = this.month;
      this.oldMonth.className = 'month out ' + (self.next ? 'next' : 'prev');
      this.oldMonth.addEventListener('webkitAnimationEnd', function() {
        self.oldMonth.parentNode.removeChild(self.oldMonth);
        self.month = createElement('div', 'month');
        self.backFill();
        self.currentMonth();
        self.fowardFill();
        self.el.appendChild(self.month);
        window.setTimeout(function() {
          self.month.className = 'month in ' + (self.next ? 'next' : 'prev');
        }, 16);
      });
    } else {
      this.month = createElement('div', 'month');
      this.el.appendChild(this.month);
      this.backFill();
      this.currentMonth();
      this.fowardFill();
      this.month.className = 'month new';
    }
  }

  Calendar.prototype.backFill = function() {
    var clone = this.current.clone();
    var dayOfWeek = clone.day();

    if (!dayOfWeek) {
      return;
    }

    clone.subtract('days', dayOfWeek + 1);

    for (var i = dayOfWeek; i > 0; i--) {
      this.drawDay(clone.add('days', 1));
    }
  }

  Calendar.prototype.fowardFill = function() {
    var clone = this.current.clone().add('months', 1).subtract('days', 1);
    var dayOfWeek = clone.day();

    if (dayOfWeek === 6) {
      return;
    }

    for (var i = dayOfWeek; i < 6; i++) {
      this.drawDay(clone.add('days', 1));
    }
  }

  Calendar.prototype.currentMonth = function() {
    var clone = this.current.clone();

    while (clone.month() === this.current.month()) {
      this.drawDay(clone);
      clone.add('days', 1);
    }
  }

  Calendar.prototype.getWeek = function(day) {
    if (!this.week || day.day() === 0) {
      this.week = createElement('div', 'week');
      this.month.appendChild(this.week);
    }
  }

  Calendar.prototype.drawDay = function(day) {
    var self = this;
    this.getWeek(day);

    var todayEvents = this.events.filter(function(event){
      return event.date.isSame(day, 'day');
    })[0];

    //Outer Day
    var outer = createElement('div', this.getDayClass(day));
    var circle = createElement('span', 'circle');
    if(todayEvents) {
      outer.addEventListener('click', function() {
        self.openDay(this);
      });
      // Circle
      var size = (1 / this.maxEvents) * todayEvents.events.length;
      circle.style.webkitTransform = 'scale(' + size + ')';
      circle.style.MozProperty = 'scale(' + size + ')';
      circle.style.transform = 'scale(' + size + ')';
    } else {
      circle.style.webkitTransform = 'scale(0, 0)';
      circle.style.MozProperty = 'scale(0, 0)';
      circle.style.transform = 'scale(0, 0)';
      outer.style.cursor = 'default';
    }

    //Day Name
    var name = createElement('div', 'day-name', day.format('ddd'));

    //Day Number
    var number = createElement('div', 'day-number', day.format('DD'));

    //Events
    var events = createElement('div', 'day-events');
    this.drawEvents(day, events);

    //outer.appendChild(name);
    outer.appendChild(circle);
    outer.appendChild(number);
    //outer.appendChild(events);
    this.week.appendChild(outer);
  }

  Calendar.prototype.drawEvents = function(day, element) {
    if (day.month() === this.current.month()) {
      var todaysEvents = this.events.reduce(function(memo, ev) {
        if (ev.date.isSame(day, 'day')) {
          memo.push(ev);
        }
        return memo;
      }, []);

      todaysEvents.forEach(function(ev) {
        var evSpan = createElement('span', ev.color);
        element.appendChild(evSpan);
      });
    }
  }

  Calendar.prototype.getDayClass = function(day) {
    classes = ['day'];
    if (day.month() !== this.current.month()) {
      classes.push('other');
    } else if (today.isSame(day, 'day')) {
      classes.push('today');
    }
    return classes.join(' ');
  }

  Calendar.prototype.openDay = function(el) {
    var details, arrow;
    var dayNumber = +el.querySelectorAll('.day-number')[0].innerText || +el.querySelectorAll('.day-number')[0].textContent;
    var day = this.current.clone().date(dayNumber);

    var currentOpened = document.querySelector('.details');

    //Check to see if there is an open detais box on the current row
    if (currentOpened && currentOpened.parentNode === el.parentNode) {
      details = currentOpened;
      arrow = document.querySelector('.arrow');
    } else {
      //Close the open events on differnt week row
      //currentOpened && currentOpened.parentNode.removeChild(currentOpened);
      if (currentOpened) {
        currentOpened.addEventListener('webkitAnimationEnd', function() {
          currentOpened.parentNode.removeChild(currentOpened);
        });
        currentOpened.addEventListener('oanimationend', function() {
          currentOpened.parentNode.removeChild(currentOpened);
        });
        currentOpened.addEventListener('msAnimationEnd', function() {
          currentOpened.parentNode.removeChild(currentOpened);
        });
        currentOpened.addEventListener('animationend', function() {
          currentOpened.parentNode.removeChild(currentOpened);
        });
        currentOpened.className = 'details out';
      }

      //Create the Details Container
      details = createElement('div', 'details in');

      //Create the arrow
      var arrow = createElement('div', 'arrow');

      //Create the event wrapper
      details.appendChild(arrow);
      el.parentNode.appendChild(details);
    }

    var todaysEvents = this.events.filter(function(event){
      return event.date.isSame(day, 'day');
    });

    console.log('m: ', todaysEvents)
    this.renderEvents(todaysEvents, details);

    arrow.style.left = el.offsetLeft - el.parentNode.offsetLeft + (el.offsetWidth / 2) + 'px';
  }

  Calendar.prototype.renderEvents = function(events, ele) {
    //Remove any events in the current details element
    var currentWrapper = ele.querySelector('.events');
    var wrapper = createElement('div', 'events in' + (currentWrapper ? ' new' : ''));

    if(events.length < 1) {
      return;
    }
    console.log("events: ", events);
    events[0].events.forEach(function(ev) {
      console.log("evv: ", ev);
      var div = createElement('div', 'event');
      var square = createElement('div', 'event-category ' + ev.color);
      var span = createElement('span', '', ev.name);

      div.appendChild(square);
      div.appendChild(span);
      wrapper.appendChild(div);
    });

    if (!events.length) {
      var div = createElement('div', 'event empty');
      var span = createElement('span', '', 'No Events');

      div.appendChild(span);
      wrapper.appendChild(div);
    }

    if (currentWrapper) {
      currentWrapper.className = 'events out';
      currentWrapper.addEventListener('webkitAnimationEnd', function() {
        currentWrapper.parentNode.removeChild(currentWrapper);
        ele.appendChild(wrapper);
      });
      currentWrapper.addEventListener('oanimationend', function() {
        currentWrapper.parentNode.removeChild(currentWrapper);
        ele.appendChild(wrapper);
      });
      currentWrapper.addEventListener('msAnimationEnd', function() {
        currentWrapper.parentNode.removeChild(currentWrapper);
        ele.appendChild(wrapper);
      });
      currentWrapper.addEventListener('animationend', function() {
        currentWrapper.parentNode.removeChild(currentWrapper);
        ele.appendChild(wrapper);
      });
    } else {
      ele.appendChild(wrapper);
    }
  }

  Calendar.prototype.drawWeekDays = function(el) {
    var self = this;
    this.weekDays = createElement('div', 'week-days')
    var weekdays = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT']
    weekdays.forEach(function(weekday){
      var day = createElement('span', 'day', weekday);
      self.weekDays.appendChild(day);
    })
    this.el.appendChild(this.weekDays);
  }

  Calendar.prototype.drawLegend = function() {
    var legend = createElement('div', 'legend');
    var calendars = this.events.map(function(e) {
      return e.calendar + '|' + e.color;
    }).reduce(function(memo, e) {
      if (memo.indexOf(e) === -1) {
        memo.push(e);
      }
      return memo;
    }, []).forEach(function(e) {
      var parts = e.split('|');
      var entry = createElement('span', 'entry ' + parts[1], parts[0]);
      legend.appendChild(entry);
    });
    this.el.appendChild(legend);
  }

  Calendar.prototype.nextMonth = function() {
    this.current.add('months', 1);
    this.next = true;
    this.draw();
  }

  Calendar.prototype.prevMonth = function() {
    this.current.subtract('months', 1);
    this.next = false;
    this.draw();
  }

  window.Calendar = Calendar;

  function createElement(tagName, className, innerText) {
    var element = document.createElement(tagName);
    if (className) {
      element.className = className;
    }
    if (innerText) {
      element.innderText = element.textContent = innerText;
    }
    return element;
  }''
}();

/****************************/
/****************************/
/****************************/
/****************************/
/****************************/

var js_tasks = JSON.parse('<?= $js_tasks; ?>');
var js_events = JSON.parse('<?= $js_events; ?>');

var tasks_and_events = [];
var dates = [];

for (var i = 0; i < js_tasks.length; i++)
{
    var currdate = js_tasks[i][0];
    
    var date_conversion = currdate.split("-");
    var year = date_conversion[0];
    var month = date_conversion[1];
    var day = date_conversion[2];
    var converted_date = new Date(year, month, day);
    
    if (dates.includes(currdate))
	{
	    for (var j = 0; j < tasks_and_events.length, j++)
		{
		    if (tasks_and_events[j].date == converted_date)
			{
			    tasks_and_events[j].events.push({
				 name: js_tasks[i][1],
				 type: 'task',
				 color: 'green',
				})
			    }
		    }
	    }
    else
	{
	    var task = {
		date: converted_date,
		events: [{
		    name: js_tasks[i][1],
		    type: 'task',
		    color: 'green',
		    }]
		}
	    
	    tasks_and_events.push(task);
	    }
}

for (var i = 0; i < js_.length; i++)
{
    var currdate = js_events[i][0];
    
    var date_conversion = currdate.split("-");
    var year = date_conversion[0];
    var month = date_conversion[1];
    var day = date_conversion[2];
    var converted_date = new Date(year, month, day);
    
    if (dates.includes(currdate))
	{
	    for (var j = 0; j < tasks_and_events.length, j++)
		{
		    if (tasks_and_events[j].date == converted_date)
			{
			    tasks_and_events[j].events.push({
				 name: js_events[i][1],
				 type: 'event',
				 color: 'pink',
				})
			    }
		    }
	    }
    else
	{
	    var vevent = {
		date: converted_date,
		events: [{
		    name: js_events[i][1],
		    type: 'event',
		    color: 'pink',
		    }]
		}
	    
	    tasks_and_events.push(vevent);
	    }
}

var app = angular.module('myApp', []);
app.controller('AppCtrl', function($scope){
  //alert("pepe")
});
app.directive('calendar', [function(){
  return {
    restrict: 'EA',
    scope: {
      date: '=',
      events: '='
    },
    link: function(scope, element, attributes) {
      var calendar = new Calendar('#calendar', tasks_and_events);
    }
  }
}]);
