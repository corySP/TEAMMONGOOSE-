<?php

/*======
   function: create_register_confirmation: void -> void
   purpose: expect nothing, and returns nothing, BUT does
            expect the $_POST array to contain a key "newemail",
	    a key "newname", a key "newpwd1", and a key "newpwd2"
	     
	     ...if it cannot find these, or the entered username already exists
	     in the database, or if the entered password does not match the confirm
	     password key, or is displays an error message
	     and returns the current user to the user login screen;
	     
	     ...otherwise it will take the entered username and password, and
	     insert them into the database and return the user to the user login screen.
   	

=====*/

function create_register_confirmation()
{
 // first: Is there at least an attempt at a username/password/password2

    if ( (! array_key_exists("newemail", $_POST)) or
         (! array_key_exists("newpwd1", $_POST)) or
	 (! array_key_exists("newpwd2", $_POST)) or
	 (! array_key_exists("newname", $_POST)) or
         ($_POST["newemail"] == "") or
	 ($_POST["newname"] == "") or
         ($_POST["newpwd1"] == "") or
	 ($_POST["newpwd2"] == "") or
         (! isset($_POST["newemail"])) or
	 (! isset($_POST["newname"])) or
	 (! isset($_POST["newpwd1"])) or
         (! isset($_POST["newpwd2"])) )
     {
         $confirmation_message = "User registration failed. Please try again...";         
     }

     elseif ( strip_tags($_POST["newpwd1"]) != strip_tags($_POST["newpwd2"]) )
     {
	$confirmation_message = "User registration failed. Passwords do not match. Please try again...";         
     }

     else
     {
     
     // Adding the elements from $_POST to local variables

     $user_email = strip_tags($_POST["newemail"]);
     $user_name = strip_tags($_POST["newname"]);
     $user_password = strip_tags($_POST["newpwd1"]);
     $user_confirm_password = strip_tags($_POST["newpwd2"]);

     // Saving the hsu credentials into local variables     
     
     $master_username = ($_SESSION["master_username"]);
     $master_password = ($_SESSION["master_password"]);

     $conn = hsu_conn_sess($master_username, $master_password);

     // Creating local variables to store the stored procdure strings and statments

     $email_check_str = 'begin :email_check_return := user_check(:f_email); end;';
     $email_check_stmt = oci_parse($conn, $email_check_str);
    
    // Creating local variables to store bind variables
     
     oci_bind_by_name($email_check_stmt, ":email_check_return",
                    $email_check_return, 4);

     oci_bind_by_name($email_check_stmt, ":f_email", $user_email);
     
     oci_execute($email_check_stmt, OCI_DEFAULT);
   
     if ($email_check_return == 1)
        {
		$confirmation_message = "User registration failed. Email Already in use. Please try again...";
	}
     elseif ($email_check_return == 0)
        {
	    $add_user_str = 'begin add_user(:p_email, :p_password, :p_name); end;';
	    $add_user_stmt = oci_parse($conn, $add_user_str);



	    

	    oci_bind_by_name($add_user_stmt, ":p_email", $user_email);
	    oci_bind_by_name($add_user_stmt, ":p_password", $user_password);
	    oci_bind_by_name($add_user_stmt, ":p_name", $user_name);

	    oci_execute($add_user_stmt, OCI_DEFAULT);
	    oci_commit($conn);
	    
	    $confirmation_message = "User registration sucessful!";
	    
	    oci_free_statement($add_user_stmt);
	}     
	

	oci_free_statement("$email_check_stmt");

	oci_free_statement($email_check_stmt);

	oci_close($conn);
     }



   ?>
       <form method="post"
              action="<?= htmlentities($_SERVER['PHP_SELF'],
                                       ENT_QUOTES) ?>">
	 <fieldset>
     	  <legend> Registration Confirmation:
       	   </legend>

     	    <p> <?= $confirmation_message ?>  </p>
     	   <input type="submit" name="confirmation-submit" value="Continue" formnovalidate />
   	  </fieldset>
	</form>
<?php

}
?>

