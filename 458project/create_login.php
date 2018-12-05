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
         (!array_key_exists("user_log_out", $_POST)) &&
	 (!array_key_exists("register-back", $_POST))) )
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

    $_SESSION["current_user"] = "none";    

    $master_username = strip_tags($_POST["username"]);
    $master_password = strip_tags($_POST["password"]);

    $_SESSION["master_username"] = $master_username;
    $_SESSION["master_password"] = $master_password;




   	   
    $_SESSION["current_user"] = -1;  

    require_once("../../_conn.php");  
    $_SESSION["master_username"] = DB_USER;
    $_SESSION["master_password"] = DB_PASS;




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
	    <input type="submit" id="exit-button" name="login-exit-button" value="Exit" formnovalidate />



	 <fieldset>
         <legend> Enter your username/password:
         </legend>
            <br />

      <label class="heading" for="name_entry"> Username: </label>
      <input type="text" name="username" id="name_entry"
             required="required" />
            <br />
            <br />

      <label class="heading" for="pwd_entry"> Password: </label>
      <input type="password" name="password" id="pwd_entry"
                       required="required" />
            <br />
            <br />

          </fieldset>

            <br />

            <input type="submit" id="submit-button" name="login-submit-button"  value="login" />



            <input type="submit" id="register-button" name="register-button" value="Register" formnovalidate />

       </fieldset>
        </form>
        <?php
}
?>
