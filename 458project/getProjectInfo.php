<?php
require("../../_conn.php");
require_once( "ProjectClass.php");
$pid = intval($_GET['project_id']);

$jsonData = ProjectClass::getProjectInfo($pid);
print $jsonData;
?>
