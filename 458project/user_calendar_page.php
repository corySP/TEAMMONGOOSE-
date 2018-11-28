<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="myApp" ng-controller="AppCtrl">

<head>
     <title> Your Calendar </title>
     <meta charset="utf-8" />

     <link href="homepage.css" type="text/css" rel="stylesheet" />
     <link href="calendar.css" type="text/css" rel="stylesheet" />

     <script src="calendar.js" type="text/javascript"> </script>
</head>

<body>
<h1>OUT</h1>
<?php
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
//function create_user_calendar_page()
//{
?>
<h1>IN</h1>
<?php
	$username = strip_tags(htmlspecialchars($_SESSION['master_username']));
	$password = strip_tags(htmlspecialchars($_SESSION['master_password']));
	$conn = hsu_conn_sess($username, $password);
	$get_tasks_str = 'select task_date, task_name
					  from   Task, Account
					  where  Task.user_id = :current_user';
	$get_tasks_stmt = oci_parse($conn, $get_tasks_str);
	
	$current_user = $_SESSION["current_user"];
	oci_bind_by_name($get_tasks_stmt, ':current_user', $current_user);
	
	oci_execute($get_tasks_stmt, OCI_DEFAULT);
 	$tasks = array();
 
	while (oci_fetch($get_tasks_stmt))
	{
		$curr_task_name = oci_result($get_tasks_stmt, 'TASK_NAME');
		$curr_task_date = oci_result($get_tasks_stmt, 'TASK_DATE');
				
		$row = array($curr_task_date, $curr_task_name);
		array_push($tasks, $row);
	}
	oci_free_statement($get_tasks_stmt);
	
	$get_events_str = 'select event_name, event_datetime
					   from   Event, Account, Event_users
					   where  Event_users.user_id = :current_user
							  and Event.event_id = Event_users.event_id';
	$get_events_stmt = oci_parse($conn, $get_events_str);
	
	oci_bind_by_name($get_events_stmt, ':current_user', $current_user);
	oci_execute($get_events_stmt, OCI_DEFAULT);
 
 	$events = array();
	while (oci_fetch($get_events_stmt))
	{
		$curr_event_name = oci_result($get_events_stmt, 'EVENT_NAME');
		$curr_event_datetime = oci_result($get_events_stmt, 'EVENT_DATETIME');
		$row = array($curr_event_datetime, $curr_event_name);
		array_push($events, $row);
	}
	oci_free_statement($get_events_stmt);
 
 	$js_tasks = json_encode($tasks);
 	$js_events = json_encode($events);
 
 	echo $js_tasks;
 	echo $js_events;
//}
?>
</body>
</html>
