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
        
    $_SESSION["current_user"] = "none";  
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
	 <fieldset>
         <legend> Enter your username/password:
         </legend>

      <label class="heading" for="name_entry"> Username: </label>
      <input type="text" name="username" id="name_entry"
             required="required" />

      <label class="heading" for="pwd_entry"> Password: </label>
      <input type="password" name="password" id="pwd_entry"
                       required="required" />
          </fieldset>
            <input type="submit" id="register-button" name="register-button" value="Register" formnovalidate />
            <input type="submit" id="submit-button" name="login-submit-button"  value="login" />
        </fieldset>
        </form>
        <?php
}
?>