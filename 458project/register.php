<?php
/*
by: Mabel Houle
last modified: 2018-11-12

show_register: void->void
purpose: creates a form for the user to provide their registration information
*/

function show_register()
{
?>
    <form method="post" action="<?= htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>">
        <fieldset>
            <legend> Enter desired information: </legend>
 
            <label for="email"> E-Mail: </label>
            <input type="text" name="newemail" id="newemail" required="required" />
 
            <label for="pwd1"> Password: </label>
            <input type="password" name="newpwd1" id="newpwd1" required="required" />
	    
            <label for="pwd2"> Confirm Password: </label>
            <input type="password" name="newpwd2" id="newpwd2" required="required" />
	    
            <input type="hidden" name="reg" id="reg" value="yes" />
	    
            <input type="submit" value="Submit" />
	</fieldset>
    </form>
<?php
}
?>
