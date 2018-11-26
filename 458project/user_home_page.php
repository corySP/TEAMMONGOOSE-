<?php

/*======
   function: create_user_home_page: void -> void
   purpose: expect nothing, and returns nothing, BUT does
            expect keys "username" and "password" in $_POST;

            if either of the keys do not exist in post, or if
            the entered username and password do not match the
            credentials in the database, the user is asked to try again
            and returned to the user login screen;

            if both keys are present and both match the credentials within
            the database, then the user will be "logged in" and the application will
            save the users email in the $_SESSION array and create the user home page form



=====*/

function create_user_home_page()
{
// first: IS there at least an attempt at a username/password?
   if ( (array_key_exists("user_to_home", $_POST)) )
   {
      $login_check_return = $_SESSION["current_user"];
   }
   
   else
   {   
	if ( (! array_key_exists("username", $_POST)) or
            (! array_key_exists("password", $_POST)) or
            ($_POST["username"] == "") or
            ($_POST["password"] == "") or
            (! isset($_POST["username"])) or
            (! isset($_POST["password"])) )
    	 {
	    $confirmation_message = "User login failed. Please try again...";
	    $login_flag = 0;
   	 }

    	 else
    	 {

       	 // Adding the elements from $_POST to local variables

       	 $entered_email = strip_tags($_POST["username"]);
       	 $entered_password = strip_tags($_POST["password"]);

       	 // Saving the hsu credentials into local variables

       	 $master_username = ($_SESSION["master_username"]);
       	 $master_password = ($_SESSION["master_password"]);

       	 $conn = hsu_conn_sess($master_username, $master_password);

       	 // Creating local variables to store the stored function strings and statments

       	 $login_check_str = 'begin :login_check_return := user_login(:f_email, :f_password); end;';
       	 $login_check_stmt = oci_parse($conn, $login_check_str);

       	 // Creating local variables to store bind variables

       	 oci_bind_by_name($login_check_stmt, ":login_check_return",
                          $login_check_return, 4);

         
         oci_bind_by_name($login_check_stmt, ":f_email", $entered_email);
       	 oci_bind_by_name($login_check_stmt, ":f_password", $entered_password);

       	 oci_execute($login_check_stmt, OCI_DEFAULT);

       	 //When user_login() fails and retuns a -1
       	 if ( $login_check_return == -1 )
       	 {
       	    $confirmation_message = "User login failed. Invalid Login Credentials Please try again...";
	    $login_flag = 0;
       	 }

       	 //When user_login() succeeds and returns the user_id (not a -1)
       	 elseif ( $login_check_return != -1 )
       	 {
             $confirmation_message = "User login sucessfull";
	      $login_flag = 1;

	      $_SESSION["current_user"] = $login_check_return;
         }
       
	oci_free_statement($login_check_stmt);
       	oci_close($conn);
   	 }
     }
    	 if ( $login_check_return == -1 )
    	 {
 
	?>
     	 <form method="post"
              action="<?= htmlentities($_SERVER['PHP_SELF'],
                                       ENT_QUOTES) ?>">
          <fieldset>
          <legend> Incorrect Login:
           </legend>
	   
	    <p> <?= $confirmation_message ?> </p>
	    <input type="submit" name="user_log_out" value="Back" formnovalidate />
         </fieldset>
	</form>
    
       <?php
        }

    	else  
    	{    

        ?>
        <form method="post"
              action="<?= htmlentities($_SERVER['PHP_SELF'],
                                       ENT_QUOTES) ?>">
         <fieldset>
          <legend> User Home Page:
           </legend>

            <p>  This is the user home page. Need to add necessary features... </p>
	    <input type="submit" name="user_log_out" value="Log Out" formnovalidate />
            <input type="submit" name="user_to_calendar" value="Calendar" formnovalidate />
	    <input type="submit" name="user_to_files" value="Files" formnovalidate />	    
          </fieldset>
        </form>
    <?php
    }
}
?>
