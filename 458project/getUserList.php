<?php
require("../../_conn.php");

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

$q_proj = "select * from Account";
$proj_stmt = oci_parse($db_conn, $q_proj);
oci_execute($proj_stmt, OCI_DEFAULT);

$line = new stdClass;
while (oci_fetch($proj_stmt)) {
    $line->user_id = oci_result($proj_stmt, "USER_ID");
    $line->user_name = oci_result($proj_stmt, "USER_NAME");
    $arr[] = json_encode($line);
}

//oci_free_statement($task_stmt);
oci_free_statement($proj_stmt);
oci_close($db_conn);

$jsonData .= implode(",", $arr);
$jsonData .= ']}';
print $jsonData;
?>
