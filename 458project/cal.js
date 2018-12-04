
$(document).ready( function() {
    var $calBtn = $("#calendarBtn");
    var $maindiv = $("#contents");

    $calBtn.click( function() {
        $maindiv.empty();
        $maindiv.append('<div id="calcont" ng-app="myApp" ng-controller="AppCtrl"> <div calendar class="calendar" id="calendar"></div></div>');
        getCal();
    });

});

function getCal() {
    $.get("./calendarHelper.php", {
        text: "hello"
    }, function(data) {
        makeCal(data);
    });
}

function makeCal(dataIn) {
    var js_data = JSON.parse(dataIn);
    //var js_tasks = JSON.parse('<?php echo $js_tasks; ?>');
    //var js_tasks = JSON.parse(data);
    //var js_events = JSON.parse(data);
    //var js_events = JSON.parse('<?php echo $js_events; ?>');
    var js_tasks = js_data[0];
    var js_events = js_data[1];
    var tasks_and_events = [];
    var dates = [];

    for (var i = 0; i < js_tasks.length; i++)
    {
        var currdate = js_tasks[i][0];
        var date_conversion = currdate.split("-");
        var year = 2000 + parseInt(date_conversion[2]);
        var mon = date_conversion[1];
        var day = parseInt(date_conversion[0]);
        $("#contents").append('<p>' + currdate + '</p>');

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
        $("#contents").append('<p>' + currdate + '</p>');

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
}
