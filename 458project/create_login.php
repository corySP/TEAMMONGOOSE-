<?php


/*=====
    Starting STUB

function create_login()
{
    ?>
    <p> called create_login() </p>
    <?php
}
=====*/

/*=====
    function: create_login: void -> void
    purpose: expects nothing, returns nothing, and has the side-effect
        of outputting to the resulting document an Oracle log-on form
        with method="post" and action equal to the calling PHP document

    requires: name-pwd-fieldset.html, 328footer.html
=====*/

function create_login()
{
    if ( (!array_key_exists("confirmation-submit", $_POST) &&
         (!array_key_exists("user_log_out", $_POST))))
     {   
	 if ( (! array_key_exists("username", $_POST)) or
         (! array_key_exists("password", $_POST)) or
         ($_POST["username"] == "") or
         ($_POST["password"] == "") or
         (! isset($_POST["username"])) or
         (! isset($_POST["password"])) )
   	    {
              destroy_and_exit("must enter a username and password!");
   	    }

    $master_username = strip_tags($_POST["username"]);
    $master_password = strip_tags($_POST["password"]);

    $_SESSION["master_username"] = $master_username;
    $_SESSION["master_password"] = $master_password;

    }
    // create the desired Oracle log-in form
    ?>
        <form method="post"
              action="<?= htmlentities($_SERVER['PHP_SELF'],
                                       ENT_QUOTES) ?>">
        <fieldset>
        <?php
        require_once("name-pwd-fieldset.html");
        ?>
            <input type="submit" id="register-button" name="register-button" value="Register" formnovalidate />
            <input type="submit" id="submit-button" name="login-submit-button"  value="login" />
        </fieldset>
        </form>
        <?php
}



?>
