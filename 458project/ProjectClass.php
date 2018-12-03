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
}
?>
