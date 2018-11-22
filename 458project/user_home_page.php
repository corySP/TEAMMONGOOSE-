<?php

/*======
   function: create_user_home_page: void -> void
   purpose: expect nothing, and returns nothing, BUT does
            expect the $_POST array to contain a key "username"
            with a valud Oracle username, and a key "password"
            with a valid Oracle password;




=====*/

function create_user_home_page()
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
?>
