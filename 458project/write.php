<?php
session_start();
require("../../_conn.php");
require_once("ChatClass.php");

$chat_username = strip_tags($_SESSION["current_username"]);
$chat_text = strip_tags($_GET["text"]);
ChatClass::sendMessage($chat_username, $chat_text);
?>

