<?php
session_start();

// The following require is for credentials saved in another file off
// web root. This will make chat work correctly because the website would have
// exaclty one db account and everyone can post and get messages from that single
// db
//require("../../_conn.php");

// This is for the two login solution, it *breaks* chatting because everyone will
// be posting to separate tables on their own database account
$db_username = $_SESSION["master_username"];
$db_pass = $_SESSION["master_password"];

$username = strip_tags($_GET["username"]);
$text = strip_tags($_GET["text"]);

$db_conn_str =
    "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                               (HOST = cedar.humboldt.edu)
                               (PORT = 1521))
                    (CONNECT_DATA = (SID = STUDENT)))";
$db_conn = oci_connect($db_username, $db_pass, $db_conn_str);

if (! $db_conn) {
    echo "Failed to connect";
}

$insert_message = "insert into Chat_message
                   values
                   (0, :chat_username, :new_message)";
$insert_stmt = oci_parse($db_conn, $insert_message);
oci_bind_by_name($insert_stmt, ":chat_username", $username);
oci_bind_by_name($insert_stmt, ":new_message", $text);

$res = oci_execute($insert_stmt, OCI_DEFAULT);
if ($res == 0) {
    echo "No row inserted";
} else {
    echo "row inserted";
    oci_commit($db_conn);
}

oci_free_statement($insert_stmt);
oci_close($db_conn);
?>

