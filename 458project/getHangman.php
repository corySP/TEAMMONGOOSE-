<?php
session_start();
require("../../_conn.php");
require_once('./HangClass.php');

HangClass::genHangman();
$jsonData = HangClass::getHangman();
print $jsonData;
?>
