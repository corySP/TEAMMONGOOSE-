<?php
session_start();
require("../../_conn.php");
require_once( "ProjectClass.php");
$usrid = 1;
if (array_key_exists('current_user', $_SESSION))
{
    $usrid = intval( $_SESSION['current_user']);
}

$jsonData = ProjectClass::getProjects();
print $jsonData;
?>
