<?php
/*======
   function: create_user_calendar_page: void -> void
   purpose: expect nothing, and returns nothing, BUT does
            expect the $_SESSION array to contain a key "username"
            with a valid Oracle username, and a key "password"
            with a valid Oracle password;
=====*/
function buCal($current_user)
{
    $db_conn_str =
        "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                   (HOST = cedar.humboldt.edu)
                                   (PORT = 1521))
                        (CONNECT_DATA = (SID = STUDENT)))";
    $conn = oci_connect(DB_USER, DB_PASS, $db_conn_str);
	$get_tasks_str = 'select project_name, task_name, task_date, current_status, 
							 task_description, user_comment
					  from   Project, Task, Account
					  where  Task.user_id = :current_user
							 and Project.project_id = Task.project_id';
	$get_tasks_stmt = oci_parse($conn, $get_tasks_str);
	
	oci_bind_by_name($get_tasks_stmt, ':current_user', $current_user);
	
	oci_execute($get_tasks_stmt, OCI_DEFAULT);
 	$arr = array();
    $jsonData = '{"results":[';
 
    $line = new stdClass;
	while (oci_fetch($get_tasks_stmt))
	{
		$line->cname = oci_result($get_tasks_stmt, 'TASK_NAME');
		$line->cdate = oci_result($get_tasks_stmt, 'TASK_DATE');
        $arr[] = json_encode($line);
	}
	oci_free_statement($get_tasks_stmt);
	
	$get_events_str = 'select event_name, event_datetime
					   from   Event, Account, Event_users
					   where  Event_users.user_id = :current_user
							  and Event.event_id = Event_users.event_id';
	$get_events_stmt = oci_parse($conn, $get_events_str);
	
	oci_bind_by_name($get_events_stmt, ':current_user', $current_user);
	oci_execute($get_events_stmt, OCI_DEFAULT);
 
	while (oci_fetch($get_events_stmt))
	{
		$line->cname = oci_result($get_events_stmt, 'EVENT_NAME');
		$line->cdate = oci_result($get_events_stmt, 'EVENT_DATETIME');
        $arr[] = json_encode($line);
	}
	oci_free_statement($get_events_stmt);
    oci_close($conn);
    $jsonData .= implode(",", $arr);
    $jsonData .= ']}';
    return $jsonData;
}
?>
