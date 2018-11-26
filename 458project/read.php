<?php
// The following require is for credentials saved in another file off
// web root. This will make chat work correctly because the website would have
// exaclty one db account and everyone can post and get messages from that single
// db
//require("../../_conn.php");
session_start();

$lastId = 0;
if (array_key_exists('lastId', $_GET))
{
    $lastId = intval( $_GET['lastId'] );
}

// This is for the two login solution, it *breaks* chatting because everyone will
// be posting to separate tables on their own database account
$db_username = $_SESSION["master_username"];
$db_pass = $_SESSION["master_password"];

$db_conn_str =
    "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                               (HOST = cedar.humboldt.edu)
                               (PORT = 1521))
                    (CONNECT_DATA = (SID = STUDENT)))";
$db_conn = oci_connect($db_username, $db_pass, $db_conn_str);

if (! $db_conn) {
    echo "Failed to connect";
}

$query_messages = "select * from Chat_message 
                   where message_id > :last_id 
                   order by message_id asc";
$query_stmt = oci_parse($db_conn, $query_messages);
oci_bind_by_name($query_stmt, ":last_id", $lastId);
oci_execute($query_stmt, OCI_DEFAULT);

while (oci_fetch($query_stmt)) {
    $curr_user = oci_result($query_stmt, "USER_NAME");
    $curr_text = oci_result($query_stmt, "MESSAGE_TEXT");

    echo "<div class=\"chatMessage\"> <b>$curr_user</b>: $curr_text </div> \n";
}

oci_free_statement($query_stmt);
oci_close($db_conn);
?>
