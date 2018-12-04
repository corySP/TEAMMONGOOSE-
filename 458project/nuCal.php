<?php
function nuCal($current_user, $conn) {
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
        $curr_event_datetime = (string)(oci_result($get_events_stmt, 'EVENT_DATETIME'));
        $row = array($curr_event_datetime, $curr_event_name);
        array_push($events, $row);
    }

    oci_free_statement($get_events_stmt);

    
    $js_data = array();
    array_push($js_data, $tasks);
    array_push($js_data, $events);
    $results = json_encode($js_data);
    return $results;
    

    //$js_tasks = json_encode($tasks);
    //$js_events = json_encode($events);
    //return $js_tasks;
}
?>
