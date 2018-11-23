
<?php

/*======
   function: create_user_file_page: void -> void
   purpose: expect nothing, and returns nothing, BUT does
            expect the $_POST array to contain a key "username"
            with a valud Oracle username, and a key "password"
            with a valid Oracle password;




=====*/

function create_user_file_page()
{
   ?>
       <form method="post"
              action="<?= htmlentities($_SERVER['PHP_SELF'],
                                       ENT_QUOTES) ?>">
         <fieldset>
          <legend> User File Page:
           </legend>

            <p>  This is the user file page. Need to add necessary features... </p>
            <input type="submit" name="user_log_out" value="Log Out" formnovalidate />
            <input type="submit" name="user_to_home" value="Home" formnovalidate />
            <input type="submit" name="user_to_calendar" value="Calendar" formnovalidate />
          </fieldset>
        </form>
<?php

}
?>