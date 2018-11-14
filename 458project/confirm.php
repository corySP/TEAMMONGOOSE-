<?php

/*
by: Mabel Houle
last modified: 2018-11-14

show_confirm: void -> void
purpose: displays a page informing the user whether their registration attempt
         was successful
*/

function show_confirm()
{
    if ((!array_key_exists("newname", $_POST)) 
       or ($_POST["newname"] == "")
       or (!isset($_POST["newname"])))
    {
?>
	<p>
	    Enter a username.
	</p>
<?php
	$_POST["newname"] = "";
	$_POST["newemail"] = "";
	$_POST["newpwd1"] = "";
	$_POST["newpws2"] = "";
    }    
    elseif ((!array_key_exists("newemail", $_POST)) 
       or ($_POST["newemail"] == "")
       or (!isset($_POST["newemail"])))
    {
?>
	<p>
	    Enter a username.
	</p>
<?php
	$_POST["newname"] = "";
	$_POST["newemail"] = "";
	$_POST["newpwd1"] = "";
	$_POST["newpws2"] = "";
    }    
    elseif ((!array_key_exists("newpwd1", $_POST)) 
       	   or ($_POST["newpwd1"] == "")
       	   or (!isset($_POST["newpwd1"]))
	   or (!array_key_exists("newpwd2", $_POST)) 
       	   or ($_POST["newpwd2"] == "")
       	   or (!isset($_POST["newpwd2"])))
    {
?>
	<p>
	    Enter a password.
	</p>
<?php
	$_POST["newemail"] = "";
	$_POST["newpwd1"] = "";
	$_POST["newpws2"] = "";
    }
    elseif ($_POST["newpwd1"] != $_POST["newpwd2"])
    {
?>
	<p>
	    Password fields must match.
	</p>
<?php
	$_POST["newname"] = ""
	$_POST["newemail"] = "";
	$_POST["newpwd1"] = "";
	$_POST["newpws2"] = "";
    }
    
    else
    {
	$newname = strip_tags(htmlspecialchars($_POST["newname"]));
	$newemail = strip_tags(htmlspecialchars($_POST["newemail"]));
	$newpwd = strip_tags(htmlspecialchars($_POST["newpwd1"]));

	// how do we handle oracle login here

	$conn = hsu_conn_sess(?????);

    	$user_check_call = 'begin :in_use := user_check(:user_check_email); end;';
    	$user_check_stmt = oci_parse($conn, $user_check_call);

	oci_bind_by_name($user_check_stmt, ":user_check_email", $newemail);
	oci_bind_by_name($user_check_stmt, ":in_use", $in_use, 4);

	oci_execute($user_check_stmt, OCI_DEFAULT);

	oci_free_statement($user_check_stmt);

	if ($in_use == 1)
	{
?>
	    <p>
	        Email is already in use.
	    </p>
<?php
	    $_POST["newname"] = "";
	    $_POST["newemail"] = "";
	    $_POST["newpwd1"] = "";
	    $_POST["newpws2"] = "";
	}
	else
	{
	    $add_user_call = 'begin add_user(:add_new_email, :add_new_pass, :add_new_name); end;';
	    $add_user_stmt = oci_parse($conn, $add_user_call);

	    oci_bind_by_name($add_user_stmt, ":add_new_email", $newemail);
	    oci_bind_by_name($add_user_stmt, ":add_new_pass", $newpwd);
	    oci_bind_by_name($add_user_stmt, ":add_new_name", $newname);

	    oci_execute($add_user_stmt, OCI_DEFAULT);
	    oci_commit($conn);

	    oci_free_statement($add_user_stmt);
	 
	    // check if added

	    oci_close($conn);
?>
	    <p>
	        Registration successful.
	    </p>
<?php
	}
    }
?>
    <form method="post" action="<?= htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>">
    	 <input type="submit" value="Return to Login" />
    </form>
<?php
}
?>