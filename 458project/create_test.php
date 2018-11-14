<?php

/*======
   function: create_test: void -> void
   purpose: expect nothing, and returns nothing, BUT does
            expect the $_POST array to contain a key "username"
	    with a valud Oracle username, and a key "password"
	    with a valid Oracle password;
	    
	    ...if it cannot find these, or if it cannot make a
	    valid connection to Oracle using these, it
	    destroys the session and exits;
	    
	    ...ptherwise, it tries to create a form with 
	    that displays data form the project database.

	    requires: destroy_and_exit, hsu_conn_sess.php

======*/

function create_test()
{
 // first: IS there at least an attempt at a username/password?

    if ( (! array_key_exists("username", $_POST)) or
         (! array_key_exists("password", $_POST)) or
         ($_POST["username"] == "") or
         ($_POST["password"] == "") or
         (! isset($_POST["username"])) or
         (! isset($_POST["password"])) )
    {
        destroy_and_exit("must enter a username and password!");
    }

    $username = strip_tags($_POST["username"]);

    $password = strip_tags($_POST["password"]);

    $_SESSION["username"] = $username;
    $_SESSION["password"] = $password;
   
   $master_username = ($_SESSION["master_username"]);
   $master_password = ($_SESSION["master_password"]);

   $conn = hsu_conn_sess($master_username, $master_password);

   $test_query_str ="select USER_ID, email_addr
   		    from Account
		    order by USER_ID";
   $test_query_stmt = oci_parse($conn, $test_query_str);
   oci_execute($test_query_stmt, OCI_DEFAULT);

   $login_call = 'begin :login_return := user_login(:f_username, :f_password); end;';
   $login_stmt = oci_parse($conn, $login_call);

   oci_bind_by_name($login_stmt, ":login_return",
                    $login_return, 4);

   oci_bind_by_name($login_stmt, ":f_username", $username);
   oci_bind_by_name($login_stmt, ":f_password", $password);

   oci_execute($login_stmt, OCI_DEFAULT);

  

/*======
   $user_email_str = "select email_addr
   		      from Account
		      where email_addr = '$master_username'";
   $user_email_stmt = oci_parse($conn, $user_email_str);
   oci_execute($user_email_stmt);
======*/

   ?>
   <form class= "small_sized_form" method="post"
          action="<?= htmlentities($_SERVER['PHP_SELF'],
                                   ENT_QUOTES) ?>">
      <?php
	 
	 
	 if ($login_return >= 1)
	 {
	 ?>
	
	      <fieldset>
     	       <legend> Select desired Account </legend>
     	        <select class ="account_dropdown" name="account_choice">
     		 <?php
     		  while (oci_fetch($test_query_stmt))
     		   {
		   	   $curr_account_id = oci_result($test_query_stmt, "USER_ID");
			   $current_account_email = oci_result($test_query_stmt, "EMAIL_ADDR");
	  	   ?>
	  	          <option value="<?= $curr_account_id ?>">
	        	  <?= $curr_account_id ?> : <?= $current_account_email ?> </option>
                  <?php
        }
	}
	else
	 {
        destroy_and_exit("Login Credentials Do no match!");
   	 }
	 ?>
      

      </select> </br>
      <input type="submit" class = "exit" name = "exit" value="Exit" formnovalidate />
      	     </fieldset>
	 </form>
   <?php
   oci_free_statment($user_email_stmt);
   oci_free_statement($test_query_stmt);
   oci_close($conn);

}   

?>