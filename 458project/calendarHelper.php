<?php
session_start();
require("../../_conn.php");
require_once( "buCal.php");

$lastId = 1;
if (array_key_exists('current_user', $_SESSION))
{
    $lastId = intval( $_SESSION['current_user'] );
}

$jsonData = buCal($lastId);
print $jsonData;
?>
