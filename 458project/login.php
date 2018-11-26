<?php

/* oracle_login: void -> void
 * purpose: expects nothing and returns nothing but has the side effect
 * of building an html login form
 * 
 * by Marcellus Parley
 * last modified 2018-04-26
 * */

function oracle_login()
{
?>
    <form action="<?= htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>"
        method="post">
        <fieldset>
            <legend> Oracle Login </legend>

            <div class="username_pass">
            
                <label for="username_fld"> Username: </label>
                <input type="text" name="db_user" id="username_fld" 
                    required="required" />

                <br />

                <label for="password_fld"> Password: </label>
                <input type="password" name="db_pass" id="password_fld" 
                    required="required" />

            </div>

            <input type="submit" name="login-button" value="Log in" />

        </fieldset>
    </form>
    <?php
}
?>
