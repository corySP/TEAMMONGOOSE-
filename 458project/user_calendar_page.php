<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="myApp" ng-controller="AppCtrl">

<head>
     <title> Your Calendar </title>
     <meta charset="utf-8" />

     <link href="homepage.css" type="text/css" rel="stylesheet" />
     <link href="calendar.css" type="text/css" rel="stylesheet" />

     <?php
      require_once("hsu_conn_sess.php");
      require("../../_conn.php");
     ?>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.8/angular.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<!--     <script src="calendar.js" type="text/javascript"></script> -->
     
</head>

<body>
<?php
session_start();

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);
function ShutdownHandler()
{
   if(@is_array($error = @error_get_lsat()))
   {
       return(@call_user_func_array9('ErrorHandler', $error));
   };
   return(TRUE);
};
register_shutdown_function('ShutdownHandler');
function ErrorHandler($type, $message, $file, $line)
{
   $_ERRORS = Array(
        0x0001 => 'E_ERROR',
        0x0002 => 'E_WARNING',
        0x0004 => 'E_PARSE',
        0x0008 => 'E_NOTICE',
        0x0010 => 'E_CORE_ERROR',
        0x0020 => 'E_CORE_WARNING',
        0x0040 => 'E_COMPILE_ERROR',
        0x0080 => 'E_COMPILE_WARNING',
        0x0100 => 'E_USER_ERROR',
        0x0200 => 'E_USER_WARNING',
        0x0400 => 'E_USER_NOTICE',
        0x0800 => 'E_STRICT',
        0x1000 => 'E_RECOVERABLE_ERROR',
        0x2000 => 'E_DEPRECATED',
        0x4000 => 'E_USER_DEPRECATED'
    );
    if(!@is_string($name = @array_search($type, @array_flip($_ERRORS))))
    {
        $name = 'E_UNKNOWN';
    };
    return(print(@sprintf("%s Error in file \xBB%s\xAB at line %d: %s\n", $name, @basename($file), $line, $message)));
};
$old_error_handler = set_error_handler("ErrorHandler");
/*======
   function: create_user_calendar_page: void -> void
   purpose: expect nothing, and returns nothing, BUT does
            expect the $_SESSION array to contain a key "username"
            with a valid Oracle username, and a key "password"
            with a valid Oracle password;
=====*/
?>
<h1> YOUR CALENDAR </h1>
<?php
$username = DB_USER;
$password = DB_PASS;
$conn = hsu_conn_sess(DB_USER, DB_PASS);
$get_tasks_str = 'select task_date, task_name
                  from   Task
                  where  Task.user_id = :current_user';
$get_tasks_stmt = oci_parse($conn, $get_tasks_str);
									  
      $current_user = intval(strip_tags(htmlspecialchars($_SESSION["current_user"])));
//$current_user = 00000001;
oci_bind_by_name($get_tasks_stmt, ':current_user', $current_user);
	      
oci_execute($get_tasks_stmt, OCI_DEFAULT);
 $tasks = array();
 
while (oci_fetch($get_tasks_stmt))
{
	$curr_task_name = oci_result($get_tasks_stmt, 'TASK_NAME');
	$curr_task_date = (string)(oci_result($get_tasks_stmt, 'TASK_DATE'));
					  				       
	$row = array($curr_task_date, $curr_task_name);
        array_push($tasks, $row);
}
oci_free_statement($get_tasks_stmt);
										     
/*
$get_events_str = 'select event_name, event_datetime
		   from   Event
		   where  Event_users.user_id = :current_user
			  and Event.event_id = Event_users.event_id';
 */
$get_events_str = 'select event_name, event_datetime
                   from Event';
																
$get_events_stmt = oci_parse($conn, $get_events_str);
																
//oci_bind_by_name($get_events_stmt, ':current_user', $current_user);
																
oci_execute($get_events_stmt, OCI_DEFAULT);
 
$events = array();
while (oci_fetch($get_events_stmt))
{
        $curr_event_name = oci_result($get_events_stmt, 'EVENT_NAME');
	$curr_event_datetime = (string)(oci_result($get_events_stmt, 'EVENT_DATETIME'));
	$row = array($curr_event_datetime, $curr_event_name);
	array_push($events, $row);
}

oci_free_statement($get_events_stmt);
 
$js_tasks = json_encode($tasks);
$js_events = json_encode($events);
?>

    <!-- <script src="calendar.js" type="text/javascript"></script> -->
    <div id="calcont">
     <div calendar class="calendar" id="calendar"></div>
    </div>


     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.8/angular.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script>
/*
Copyright (c) 2018 by Benjamin (https://codepen.io/maggiben/pen/OPmLBW)
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associacumentation files (the "Software"), to deal in the Software without restriction, including without limitahe rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Softwar to permit persons to whom the Software is furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all copies or substantial portf the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION TRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEAIN THE SOFTWARE.
*/
!(function() {
  var today = moment();

  function Calendar(selector, events) {
    this.el = document.querySelector(selector);
    this.events = events;
    this.maxEvents = this.events.reduce(function(p, c) {
      if (c.events.length > p) {
        return c.events.length;
      } else {
        return p;
      }
    }, 0);
    this.current = moment().date(1);
    this.draw();
    var current = document.querySelector(".today");
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
  };

  Calendar.prototype.drawHeader = function() {
    var self = this;
    if (!this.header) {
      //Create the header elements
      this.header = createElement("div", "header");
      this.header.className = "header";

      //this.title = createElement('h1');
      this.title = {
        month: createElement("div", "month", this.current.format("MMMM")),
        year: createElement("div", "year", this.current.format("YYYY"))
      };

      var right = createElement("div", "right");
      right.addEventListener("click", function() {
        self.nextMonth();
      });

      var left = createElement("div", "left");
      left.addEventListener("click", function() {
        self.prevMonth();
      });

      var ringLeft = createElement("div", "ring-left");
      var ringRight = createElement("div", "ring-right");
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
    this.title.month.innerHTML = this.current.format("MMMM");
    this.title.year.innerHTML = this.current.format("YYYY");
  };

  Calendar.prototype.drawMonth = function() {
    var self = this;

    this.events.forEach(function(event) {
      //ev.date = self.current.clone().date(Math.random() * (29 - 1) + 1);
      event.date = moment(event.date);
    });

    if (this.month) {
      this.oldMonth = this.month;
      this.oldMonth.className = "month out " + (self.next ? "next" : "prev");
      this.oldMonth.addEventListener("webkitAnimationEnd", function() {
        self.oldMonth.parentNode.removeChild(self.oldMonth);
        self.month = createElement("div", "month");
        self.backFill();
        self.currentMonth();
        self.fowardFill();
        self.el.appendChild(self.month);
        window.setTimeout(function() {
          self.month.className = "month in " + (self.next ? "next" : "prev");
        }, 16);
      });
    } else {
      this.month = createElement("div", "month");
      this.el.appendChild(this.month);
      this.backFill();
      this.currentMonth();
      this.fowardFill();
      this.month.className = "month new";
    }
  };

  Calendar.prototype.backFill = function() {
    var clone = this.current.clone();
    var dayOfWeek = clone.day();

    if (!dayOfWeek) {
      return;
    }

    clone.subtract("days", dayOfWeek + 1);

    for (var i = dayOfWeek; i > 0; i--) {
      this.drawDay(clone.add("days", 1));
    }
  };

  Calendar.prototype.fowardFill = function() {
    var clone = this.current
      .clone()
      .add("months", 1)
      .subtract("days", 1);
    var dayOfWeek = clone.day();

    if (dayOfWeek === 6) {
      return;
    }

    for (var i = dayOfWeek; i < 6; i++) {
      this.drawDay(clone.add("days", 1));
    }
  };

  Calendar.prototype.currentMonth = function() {
    var clone = this.current.clone();

    while (clone.month() === this.current.month()) {
      this.drawDay(clone);
      clone.add("days", 1);
    }
  };

  Calendar.prototype.getWeek = function(day) {
    if (!this.week || day.day() === 0) {
      this.week = createElement("div", "week");
      this.month.appendChild(this.week);
    }
  };

  Calendar.prototype.drawDay = function(day) {
    var self = this;
    this.getWeek(day);

    var todayEvents = this.events.filter(function(event) {
      return event.date.isSame(day, "day");
    })[0];

    //Outer Day
    var outer = createElement("div", this.getDayClass(day));
    var circle = createElement("span", "circle");
    if (todayEvents) {
      outer.addEventListener("click", function() {
        self.openDay(this);
      });
      // Circle
      var size = 1 / this.maxEvents * todayEvents.events.length;
      circle.style.webkitTransform = "scale(" + size + ")";
      circle.style.MozProperty = "scale(" + size + ")";
      circle.style.transform = "scale(" + size + ")";
    } else {
      circle.style.webkitTransform = "scale(0, 0)";
      circle.style.MozProperty = "scale(0, 0)";
      circle.style.transform = "scale(0, 0)";
      outer.style.cursor = "default";
    }

    //Day Name
    var name = createElement("div", "day-name", day.format("ddd"));

    //Day Number
    var number = createElement("div", "day-number", day.format("DD"));

    //Events
    var events = createElement("div", "day-events");
    this.drawEvents(day, events);

    //outer.appendChild(name);
    outer.appendChild(circle);
    outer.appendChild(number);
    //outer.appendChild(events);
    this.week.appendChild(outer);
  };

  Calendar.prototype.drawEvents = function(day, element) {
    if (day.month() === this.current.month()) {
      var todaysEvents = this.events.reduce(function(memo, ev) {
        if (ev.date.isSame(day, "day")) {
          memo.push(ev);
        }
        return memo;
      }, []);

      todaysEvents.forEach(function(ev) {
        var evSpan = createElement("span", ev.color);
        element.appendChild(evSpan);
      });
    }
  };

  Calendar.prototype.getDayClass = function(day) {
    classes = ["day"];
    if (day.month() !== this.current.month()) {
      classes.push("other");
    } else if (today.isSame(day, "day")) {
      classes.push("today");
    }
    return classes.join(" ");
  };

  Calendar.prototype.openDay = function(el) {
    var details, arrow;
    var dayNumber =
      +el.querySelectorAll(".day-number")[0].innerText ||
      +el.querySelectorAll(".day-number")[0].textContent;
    var day = this.current.clone().date(dayNumber);

    var currentOpened = document.querySelector(".details");

    //Check to see if there is an open detais box on the current row
    if (currentOpened && currentOpened.parentNode === el.parentNode) {
      details = currentOpened;
      arrow = document.querySelector(".arrow");
    } else {
      //Close the open events on differnt week row
      //currentOpened && currentOpened.parentNode.removeChild(currentOpened);
      if (currentOpened) {
        currentOpened.addEventListener("webkitAnimationEnd", function() {
          currentOpened.parentNode.removeChild(currentOpened);
        });
        currentOpened.addEventListener("oanimationend", function() {
          currentOpened.parentNode.removeChild(currentOpened);
        });
        currentOpened.addEventListener("msAnimationEnd", function() {
          currentOpened.parentNode.removeChild(currentOpened);
        });
        currentOpened.addEventListener("animationend", function() {
          currentOpened.parentNode.removeChild(currentOpened);
        });
        currentOpened.className = "details out";
      }

      //Create the Details Container
      details = createElement("div", "details in");

      //Create the arrow
      var arrow = createElement("div", "arrow");

      //Create the event wrapper
      details.appendChild(arrow);
      el.parentNode.appendChild(details);
    }

    var todaysEvents = this.events.filter(function(event) {
      return event.date.isSame(day, "day");
    });

    console.log("m: ", todaysEvents);
    this.renderEvents(todaysEvents, details);

    arrow.style.left =
      el.offsetLeft - el.parentNode.offsetLeft + el.offsetWidth / 2 + "px";
  };

  Calendar.prototype.renderEvents = function(events, ele) {
    //Remove any events in the current details element
    var currentWrapper = ele.querySelector(".events");
    var wrapper = createElement(
      "div",
      "events in" + (currentWrapper ? " new" : "")
    );

    if (events.length < 1) {
      return;
    }
    console.log("events: ", events);
    events[0].events.forEach(function(ev) {
      console.log("evv: ", ev);
      var div = createElement("div", "event");
      var square = createElement("div", "event-category " + ev.color);
      var span = createElement("span", "", ev.name);

      div.appendChild(square);
      div.appendChild(span);
      wrapper.appendChild(div);
    });

    if (!events.length) {
      var div = createElement("div", "event empty");
      var span = createElement("span", "", "No Events");

      div.appendChild(span);
      wrapper.appendChild(div);
    }

    if (currentWrapper) {
      currentWrapper.className = "events out";
      currentWrapper.addEventListener("webkitAnimationEnd", function() {
        currentWrapper.parentNode.removeChild(currentWrapper);
        ele.appendChild(wrapper);
      });
      currentWrapper.addEventListener("oanimationend", function() {
        currentWrapper.parentNode.removeChild(currentWrapper);
        ele.appendChild(wrapper);
      });
      currentWrapper.addEventListener("msAnimationEnd", function() {
        currentWrapper.parentNode.removeChild(currentWrapper);
        ele.appendChild(wrapper);
      });
      currentWrapper.addEventListener("animationend", function() {
        currentWrapper.parentNode.removeChild(currentWrapper);
        ele.appendChild(wrapper);
      });
    } else {
      ele.appendChild(wrapper);
    }
  };

  Calendar.prototype.drawWeekDays = function(el) {
    var self = this;
    this.weekDays = createElement("div", "week-days");
    var weekdays = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];
    weekdays.forEach(function(weekday) {
      var day = createElement("span", "day", weekday);
      self.weekDays.appendChild(day);
    });
    this.el.appendChild(this.weekDays);
  };

  Calendar.prototype.drawLegend = function() {
    var legend = createElement("div", "legend");
    var calendars = this.events
      .map(function(e) {
        return e.calendar + "|" + e.color;
      })
      .reduce(function(memo, e) {
        if (memo.indexOf(e) === -1) {
          memo.push(e);
        }
        return memo;
      }, [])
      .forEach(function(e) {
        var parts = e.split("|");
        var entry = createElement("span", "entry " + parts[1], parts[0]);
        legend.appendChild(entry);
      });
    this.el.appendChild(legend);
  };

  Calendar.prototype.nextMonth = function() {
    this.current.add("months", 1);
    this.next = true;
    this.draw();
  };

  Calendar.prototype.prevMonth = function() {
    this.current.subtract("months", 1);
    this.next = false;
    this.draw();
  };

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
  }
  ("");
})();

/****************************/
/****************************/
/****************************/
/****************************/
/****************************/
var js_tasks = JSON.parse('<?php echo $js_tasks; ?>');
var js_events = JSON.parse('<?php echo $js_events; ?>');
var tasks_and_events = [];
var dates = [];

for (var i = 0; i < js_tasks.length; i++)
{
    var currdate = js_tasks[i][0];
    var date_conversion = currdate.split("-");
    var year = 2000 + parseInt(date_conversion[2]);
    var mon = date_conversion[1];
    var day = parseInt(date_conversion[0]);

var month;
    if (mon == "JAN")
    {
        month = 0;
    }
    else if (mon == "FEB")
    {
        month = 1;
    }
    else if (mon == "MAR")
    {
        month = 2;
    }
    else if (mon == "APR")
    {
        month = 3;
    }
    else if (mon == "MAY")
    {
        month = 4;
    }
    else if (mon == "JUN")
    {
        month = 5;
    }
    else if (mon == "JUL")
    {
        month = 6;
    }
    else if (mon == "AUG")
    {
        month = 7;
    }
    else if (mon == "SEP")
    {
        month = 8;
    }
    else if (mon == "OCT")
    {
        month = 9;
    }
    else if (mon == "NOV")
    {
        month = 10;
    }
    else if (mon == "DEC")
    {
        month = 11;
    }
 

    var converted_date = new Date(year, month, day);
    if (dates.includes(currdate))
        {
	    var length = tasks_and_events.length;
            for (var j = 0; j < length; j++)
                {
                    if (tasks_and_events[j].date.getTime() == converted_date.getTime())
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
	        dates.push(currdate);
            }
}

for (var i = 0; i < js_events.length; i++)
{
    var currdate = js_events[i][0];
    var date_conversion = currdate.split("-");
    var year = 2000 + parseInt(date_conversion[2]);
    var mon = date_conversion[1];
    var day = parseInt(date_conversion[0]);

var month;
    if (mon == "JAN")
    {
        month = 0;
    }
    else if (mon == "FEB")
    {
        month = 1;
    }
    else if (mon == "MAR")
    {
        month = 2;
    }
    else if (mon == "APR")
    {
        month = 3;
    }
    else if (mon == "MAY")
    {
        month = 4;
    }
    else if (mon == "JUN")
    {
        month = 5;
    }
    else if (mon == "JUL")
    {
        month = 6;
    }
    else if (mon == "AUG")
    {
        month = 7;
    }
    else if (mon == "SEP")
    {
        month = 8;
    }
    else if (mon == "OCT")
    {
        month = 9;
    }
    else if (mon == "NOV")
    {
        month = 10;
    }
    else if (mon == "DEC")
    {
        month = 11;
    }

    var converted_date = new Date(year, month, day);

    if (dates.includes(currdate))
        {
          var length = tasks_and_events.length;
            for (var j = 0; j < length; j++)
                {
                if (tasks_and_events[j].date.getTime() == converted_date.getTime())
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
	        dates.push(currdate);
            }
}
var test = tasks_and_events.toString();
var app = angular.module('myApp', []);
app.controller('AppCtrl', function($scope){
 // alert("pepe")
// $window.location = 'http://nrs-projects.humboldt.edu/~mfh128/458project/user_calendar_page.php'
});
app.directive('calendar', [function(){
  //$window.alert("app");
  return {
    restrict: 'EA',
    scope: {
      date: '=',
      events: '='
    },
    link: function(scope, element, attributes) {
      var data = tasks_and_events;
      var calendar = new Calendar('#calendar', data);
    }
  };
}]);
</script>
</body>
</html>
