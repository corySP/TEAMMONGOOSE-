<?php
require("../../_conn.php");
require_once( "ProjectClass.php");
$pid = intval(strip_tags($_GET['project_id']));

$jsonData = ProjectClass::getProjectTasks($pid);
print $jsonData;
?>
