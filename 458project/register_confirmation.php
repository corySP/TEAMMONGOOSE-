<?php

/*======
   function: create_register_confirmation: void -> void
   purpose: expect nothing, and returns nothing, BUT does
            expect the $_POST array to contain a key "username"
            with a valud Oracle username, and a key "password"
            with a valid Oracle password;




=====*/

function create_register_confirmation()
{
   ?>
       <form method="post"
              action="<?= htmlentities($_SERVER['PHP_SELF'],
                                       ENT_QUOTES) ?>">
	 <fieldset>
     	  <legend> Registration Confirmation:
       	   </legend>

     	    <p>  Confirmation Successful. Returning to Login Screen... </p>
     	   <input type="submit" name="confirmation-submit" value="Continue" formnovalidate />
   	  </fieldset>
	</form>
<?php

}
?>

