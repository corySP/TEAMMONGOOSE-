<?php
session_start();
require("../../_conn.php");
require_once( "nuCal.php");

$db_conn_str =
    "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                               (HOST = cedar.humboldt.edu)
                               (PORT = 1521))
                    (CONNECT_DATA = (SID = STUDENT)))";
$db_conn = oci_connect(DB_USER, DB_PASS, $db_conn_str);

$lastId = 1;
if (array_key_exists('current_user', $_SESSION))
{
    $lastId = intval( $_SESSION['current_user'] );
}

$jsonData = nuCal($lastId, $db_conn);

oci_close($db_conn);

print $jsonData;
?>
