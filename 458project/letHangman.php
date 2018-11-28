<?php
session_start();
require("../../_conn.php");
require_once("HangClass.php");
$letter = strip_tags($_GET["letter"]);
HangClass::genHangman();
HangClass::sendLetter("$letter");
?>
