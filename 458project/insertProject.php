<?php
require("../../_conn.php");
require_once( "ProjectClass.php");
$pname = strip_tags($_GET['project_name']);
$pdesc = strip_tags($_GET['project_description']);

ProjectClass::insertProject($pname, $pdesc);

?>
