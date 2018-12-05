<?php
require("../../_conn.php");
require_once( "ProjectClass.php");
$tname = strip_tags($_GET['task_name']);
$tdesc = strip_tags($_GET['task_description']);
$usr_id = strip_tags($_GET['user_id']);
$proj_id = strip_tags($_GET['project_id']);
$tdate = strip_tags($_GET['task_date']);

ProjectClass::insertTask($proj_id, $usr_id, $tname, $tdesc, $tdate);

?>
