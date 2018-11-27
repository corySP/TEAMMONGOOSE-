<?php
session_start();
require("../../_conn.php");
require_once( "ChatClass.php");

$lastId = 0;
if (array_key_exists('lastID', $_GET))
{
    $lastId = intval( $_GET['lastID'] );
}

$jsonData = ChatClass::getMessages($lastId);
print $jsonData;
?>
