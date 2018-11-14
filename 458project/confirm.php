<?php

/*
by: Mabel Houle
last modified: 2018-11-12

show_confirm: void -> void
purpose: displays a page informing the user whether their registration attempt
         was successful
*/

function show_confirm()
{
    if ((!array_key_exists("newemail", $_POST)) 
       or ($_POST["newemail"] == "")
       or (!isset($_POST["newemail"])))
    {
?>
	<p>
	    Enter a username.
	</p>
<?php
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
	$_POST["newemail"] = "";
	$_POST["newpwd1"] = "";
	$_POST["newpws2"] = "";
    }
// check if used
    else
    {
	$newemail = strip_tags(htmlspecialchars($_POST["newemail"]));
	$_SESSION["newemail"] = $newemail;

	$newpwd = strip_tags(htmlspecialchars($_POST["newpwd1"]));
	$_SESSION["newpwd"] = $newpwd;

	// add to database
?>
	<p>
	    Registration successful.
	</p>
<?php
    }
?>
    <form method="post" action="<?= htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>">
    	 <input type="submit" value="Return to Login" />
    </form>
<?php
}
?>