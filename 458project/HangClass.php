<?php
class HangClass
{
    public static function delHangman()
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

        $del_str = "delete from Hangman";
        $del_stmt = oci_parse($db_conn, $del_str);
        oci_execute($del_stmt);
        oci_commit($db_conn);
        oci_free_statement($del_stmt);
        oci_close($db_conn);
    }

    public static function genHangman()
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

        $query_num = "select count(*) from Hangman";
        $query_stmt = oci_parse($db_conn, $query_num);
        oci_execute($query_stmt, OCI_DEFAULT);
        oci_fetch($query_stmt);
        $counter = oci_result($query_stmt, "COUNT(*)");

        if ($counter == 0)
        {
            $file_contents = file_get_contents('./wordlist.txt');
            $word_array = explode(',', $file_contents);
            unset($word_array[count($word_array)-1]);
            $word = $word_array[array_rand($word_array)];
            $word_blanked = "";

            for ($i = 0; $i < strlen($word); $i++)
            {
                $word_blanked .= "_";
            }

            $insert_message = "insert into Hangman
                               values
                               (0, :new_word, :new_prog, 0, 0)";
            $insert_stmt = oci_parse($db_conn, $insert_message);
            oci_bind_by_name($insert_stmt, ":new_word", $word);
            oci_bind_by_name($insert_stmt, ":new_prog", $word_blanked);
            oci_execute($insert_stmt, OCI_DEFAULT);
            oci_commit($db_conn);
            oci_free_statement($insert_stmt);
        }
        oci_close($db_conn);
    }

    public static function getHangman()
    {
        //genHangman();
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
/*
        $query_num = "select count(*) from Hangman";
        $query_stmt = oci_parse($db_conn, $query_num);
        oci_execute($query_stmt, OCI_DEFAULT);
        oci_fetch($query_stmt);
        $counter = oci_result($query_stmt, "COUNT(*)");

        if ($counter == 0)
        {
            $file_contents = file_get_contents('wordlist.txt');
            $word_array = explode(',', $file_contentes);
            $word = $word_array[array_rand($word_array)];
            $word_blanked = "";

            for ($i = 0; $i < strlen($word); $i++)
            {
                $word_blanked .= "_";
            }

            $insert_message = "insert into Hangman
                               values
                               (0, :new_word, :new_prog, 0, 0)";
            $insert_stmt = oci_parse($db_conn, $insert_message);
            oci_bind_by_name($insert_stmt, ":new_word", $word);
            oci_bind_by_name($insert_stmt, ":new_prog", $word_blanked);
            oci_execute($insert_stmt, OCI_DEFAULT);
            oci_commit($db_conn);
            oci_free_statement($insert_stmt);
        }
 */

        $query_hang = "select * from Hangman";
        $hang_stmt = oci_parse($db_conn, $query_hang);
        oci_execute($hang_stmt, OCI_DEFAULT);
        oci_fetch($hang_stmt);

        $hang = new stdClass;
        $hang->id = oci_result($hang_stmt, "HANG_ID");
        $hang->word = oci_result($hang_stmt, "WORD");
        $hang->prog = oci_result($hang_stmt, "CURRENT_PROGRESS");
        $hang->level = oci_result($hang_stmt, "HANG_LEVEL");
        $hang->complete = oci_result($hang_stmt, "COMPLETE");
        $arr[] = json_encode($hang);

        //oci_free_statement($query_stmt);
        oci_free_statement($hang_stmt);
        oci_close($db_conn);

        $jsonData .= implode(",", $arr);
        $jsonData .= ']}';
        return $jsonData;
    }

    public static function sendLetter($letter)
    {
        //genHangman();
        $db_conn_str =
            "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                       (HOST = cedar.humboldt.edu)
                                       (PORT = 1521))
                            (CONNECT_DATA = (SID = STUDENT)))";
        $db_conn = oci_connect(DB_USER, DB_PASS, $db_conn_str);

        if (! $db_conn) {
            echo "Failed to connect";
        }

        $query_hang = "select * from Hangman";
        $hang_stmt = oci_parse($db_conn, $query_hang);
        oci_execute($hang_stmt, OCI_DEFAULT);
        oci_fetch($hang_stmt);

        $hang = new stdClass;
        $hang->id = oci_result($hang_stmt, "HANG_ID");
        $hang->word = oci_result($hang_stmt, "WORD");
        $hang->prog = oci_result($hang_stmt, "CURRENT_PROGRESS");
        $hang->level = oci_result($hang_stmt, "HANG_LEVEL");
        $hang->complete = oci_result($hang_stmt, "COMPLETE");

        $new_prog = "";
        $found = 0;
        for ($i = 0; $i < strlen($hang->word); $i++)
        {
            if ($hang->word[$i] == $letter)
            {
                $new_prog .= $letter;
                $found++;
            }
            else
            {
                $new_prog .= $hang->prog[$i];
            }
        }

        $new_lev = $hang->level;
        if ($found == 0)
        {
            $new_lev += 1;
        }

        $new_com = 0;
        if ($hang->word === $new_prog)
        {
            $new_com = 1;
        }

        $update_hang_str =
            "update Hangman
            set current_progress = :new_prog, hang_level = :new_lev, complete = :new_com
            where hang_id = :hang_id_d";
        $update_stmt = oci_parse($db_conn, $update_hang_str);
        oci_bind_by_name($update_stmt, ":new_prog", $new_prog);
        oci_bind_by_name($update_stmt, ":new_lev", $new_lev);
        oci_bind_by_name($update_stmt, ":new_com", $new_com);
        oci_bind_by_name($update_stmt, ":hang_id_d", $hang->id);
        oci_execute($update_stmt, OCI_DEFAULT);
        oci_commit($db_conn);

        //oci_free_statement($query_stmt);
        oci_free_statement($update_stmt);
        oci_free_statement($hang_stmt);
        oci_close($db_conn);
    }
}
