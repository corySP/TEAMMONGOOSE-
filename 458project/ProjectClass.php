<?php
class ProjectClass
{
    public static function getTasks($usid)
    {
        //genHangman();
        $arr = array();
        $jsonData = '{"results":[';
        $db_conn_str =
            "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                       (HOST = cedar.humboldt.edu)
                                       (PORT = 1521))
                            (CONNECT_DATA = (SID = STUDENT)))";
        $db_conn = oci_connect(DB_USER, DB_PASS, $db_conn_str);

        if (! $db_conn) {
            echo "Failed to connect";
        }

        $q_task = "select * from Task 
                   where user_id = :desired_uid";
        $task_stmt = oci_parse($db_conn, $q_task);
        oci_bind_by_name($task_stmt, ":desired_uid", $usid);
        oci_execute($task_stmt, OCI_DEFAULT);

        $line = new stdClass;
        while (oci_fetch($task_stmt)) {
            $line->id = oci_result($task_stmt, "TASK_ID");
            $line->current_status = oci_result($task_stmt, "CURRENT_STATUS");
            $line->task_description = oci_result($task_stmt, "TASK_DESCRIPTION");
            $line->task_date = oci_result($task_stmt, "TASK_DATE");
            $line->user_comment = oci_result($task_stmt, "USER_COMMENT");
            $line->task_name = oci_result($task_stmt, "TASK_NAME");
            $arr[] = json_encode($line);
        }

        //oci_free_statement($task_stmt);
        oci_free_statement($task_stmt);
        oci_close($db_conn);

        $jsonData .= implode(",", $arr);
        $jsonData .= ']}';
        return $jsonData;
    }

    public static function getProjects()
    {
        $arr = array();
        $jsonData = '{"results":[';
        $db_conn_str =
            "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                       (HOST = cedar.humboldt.edu)
                                       (PORT = 1521))
                            (CONNECT_DATA = (SID = STUDENT)))";
        $db_conn = oci_connect(DB_USER, DB_PASS, $db_conn_str);

        if (! $db_conn) {
            echo "Failed to connect";
        }

        $q_proj = "select * from Project";
        $proj_stmt = oci_parse($db_conn, $q_proj);
        oci_execute($proj_stmt, OCI_DEFAULT);

        $line = new stdClass;
        while (oci_fetch($proj_stmt)) {
            $line->project_id = oci_result($proj_stmt, "PROJECT_ID");
            $line->project_description = oci_result($proj_stmt, "PROJECT_DESCRIPTION");
            $line->ugroup_id = oci_result($proj_stmt, "UGROUP_ID");
            $line->project_name = oci_result($proj_stmt, "PROJECT_NAME");
            $arr[] = json_encode($line);
        }

        //oci_free_statement($task_stmt);
        oci_free_statement($proj_stmt);
        oci_close($db_conn);

        $jsonData .= implode(",", $arr);
        $jsonData .= ']}';
        return $jsonData;
    }

    public static function getProjectTasks($pid)
    {
        $arr = array();
        $jsonData = '{"results":[';
        $db_conn_str =
            "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                       (HOST = cedar.humboldt.edu)
                                       (PORT = 1521))
                            (CONNECT_DATA = (SID = STUDENT)))";
        $db_conn = oci_connect(DB_USER, DB_PASS, $db_conn_str);

        if (! $db_conn) {
            echo "Failed to connect";
        }

        $q_proj = "select task_id, task_name, user_name, current_status, task_description, task_date, user_comment 
                   from Task t, Account u
                   where project_id = :d_pid and
                         t.user_id = u.user_id";
        $proj_stmt = oci_parse($db_conn, $q_proj);
        oci_bind_by_name($proj_stmt, ":d_pid", $pid);
        oci_execute($proj_stmt, OCI_DEFAULT);

        $line = new stdClass;
        while (oci_fetch($proj_stmt)) {
            $line->task_id = oci_result($proj_stmt, "TASK_ID");
            $line->task_name = oci_result($proj_stmt, "TASK_NAME");
            $line->user_name = oci_result($proj_stmt, "USER_NAME");
            $line->current_status = oci_result($proj_stmt, "CURRENT_STATUS");
            $line->task_description = oci_result($proj_stmt, "TASK_DESCRIPTION");
            $line->task_date = oci_result($proj_stmt, "TASK_DATE");
            $line->user_comment = oci_result($proj_stmt, "USER_COMMENT");
            $arr[] = json_encode($line);
        }

        //oci_free_statement($task_stmt);
        oci_free_statement($proj_stmt);
        oci_close($db_conn);

        $jsonData .= implode(",", $arr);
        $jsonData .= ']}';
        return $jsonData;
    }

    public static function getProjectInfo($pid)
    {
        $arr = array();
        $jsonData = '{"results":[';
        $db_conn_str =
            "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                       (HOST = cedar.humboldt.edu)
                                       (PORT = 1521))
                            (CONNECT_DATA = (SID = STUDENT)))";
        $db_conn = oci_connect(DB_USER, DB_PASS, $db_conn_str);

        if (! $db_conn) {
            echo "Failed to connect";
        }

        $q_proj = "select project_name, project_description 
                   from Project
                   where project_id = :d_pid";
        $proj_stmt = oci_parse($db_conn, $q_proj);
        oci_bind_by_name($proj_stmt, ":d_pid", $pid);
        oci_execute($proj_stmt, OCI_DEFAULT);

        $line = new stdClass;
        while(oci_fetch($proj_stmt)) {
            $line->project_description = oci_result($proj_stmt, "PROJECT_DESCRIPTION");
            $line->project_name = oci_result($proj_stmt, "PROJECT_NAME");
            $arr[] = json_encode($line);
        }

        //oci_free_statement($task_stmt);
        oci_free_statement($proj_stmt);
        oci_close($db_conn);

        $jsonData .= implode(",", $arr);
        $jsonData .= ']}';
        return $jsonData;

    }

    public static function insertProject($pname, $pdesc)
    {
        $db_conn_str =
            "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                       (HOST = cedar.humboldt.edu)
                                       (PORT = 1521))
                            (CONNECT_DATA = (SID = STUDENT)))";
        $db_conn = oci_connect(DB_USER, DB_PASS, $db_conn_str);

        if (! $db_conn) {
            echo "Failed to connect";
        }

        $call_str = "begin add_project(:pname, :pdesc, 0); end;";
        $stmt = oci_parse($db_conn, $call_str);

        oci_bind_by_name($stmt, ":pname", $pname);
        oci_bind_by_name($stmt, ":pdesc", $pdesc);

        oci_execute($stmt, OCI_DEFAULT);
        oci_commit($db_conn);
        oci_free_statement($stmt);
        oci_close($db_conn);
    }
    
    public static function insertTask($pid, $uid, $tname, $tdesc, $tdate)
    {
        $db_conn_str =
            "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                       (HOST = cedar.humboldt.edu)
                                       (PORT = 1521))
                            (CONNECT_DATA = (SID = STUDENT)))";
        $db_conn = oci_connect(DB_USER, DB_PASS, $db_conn_str);

        if (! $db_conn) {
            echo "Failed to connect";
        }

        $call_str = "begin add_task(:usr_id, :pr_id, :tname, :tdesc, to_date(:tdate, 'YYYY-MM-DD')); end;";
        $stmt = oci_parse($db_conn, $call_str);

        oci_bind_by_name($stmt, ":tname", $tname);
        oci_bind_by_name($stmt, ":tdesc", $tdesc);
        oci_bind_by_name($stmt, ":usr_id", $uid);
        oci_bind_by_name($stmt, ":pr_id", $pid);
        oci_bind_by_name($stmt, ":tdate", $tdate);

        oci_execute($stmt, OCI_DEFAULT);
        oci_commit($db_conn);
        oci_free_statement($stmt);
        oci_close($db_conn);
    }
}
?>
