<?php

/*======
   function: create_homepage: void -> void
   purpose: expects nothing, and returns nothing, BUT does
            expect the $_POST array to contain a key "username"
            with a valid Oracle username, and a key "password"
            with a valid Oracle password;

            ...if it cannot find these, or if it cannot make a
            valid connection to Oracle using these, it
            destroys the session and exits;

            ...otherwise, it tries to create a form with a basic
	    homepage for the user

            requires: destroy_and_exit.php

=====*/

function create_homepage()
{



}
?>