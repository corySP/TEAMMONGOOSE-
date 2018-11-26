<?php
class ChatClass
{
    public static function getMessages($lastId)
    {
        $arr = array();
        $jsonData = '{"results":[';
        $db_conn_str =
            "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                       (HOST = cedar.humboldt.edu)
                                       (PORT = 1521))
                            (CONNECT_DATA = (SID = STUDENT)))";
        $db_conn = oci_connect(DB_USER, DB_PASS, $db_conn_str);

        if (! $db_conn) {
            echo "Failed to connect";
        }

        $query_messages = "select * from Chat_message 
                           where message_id > :last_id 
                           order by message_id asc";
        $query_stmt = oci_parse($db_conn, $query_messages);
        oci_bind_by_name($query_stmt, ":last_id", $lastId);
        oci_execute($query_stmt, OCI_DEFAULT);

        $line = new stdClass;
        while (oci_fetch($query_stmt)) {
            $line->id = oci_result($query_stmt, "MESSAGE_ID");
            $line->username = oci_result($query_stmt, "USER_NAME");
            $line->text = oci_result($query_stmt, "MESSAGE_TEXT");
            $arr[] = json_encode($line);
        }

        oci_free_statement($query_stmt);
        oci_close($db_conn);

        $jsonData .= implode(",", $arr);
        $jsonData .= ']}';
        return $jsonData;
    }

    public static function sendMessage($username, $text)
    {
        $db_conn_str =
            "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                       (HOST = cedar.humboldt.edu)
                                       (PORT = 1521))
                            (CONNECT_DATA = (SID = STUDENT)))";
        $db_conn = oci_connect(DB_USER, DB_PASS, $db_conn_str);

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
    }
}

?>
