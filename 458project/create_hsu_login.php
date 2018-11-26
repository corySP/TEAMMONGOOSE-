<?php


/*=====
    Starting STUB

function create_login()
{
    ?>
    <p> called create_hsu_login() </p>
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

function create_hsu_login()
{
    // create the desired Oracle log-in form
    ?>
        <form method="post"
              action="<?= htmlentities($_SERVER['PHP_SELF'],
                                       ENT_QUOTES) ?>">
        <fieldset>
        <?php
        require_once("name-pwd-fieldset.html");
        ?>
           
            <input type="submit" id="submit-button" value="login" />
        </fieldset>
        </form>
        <?php
}



?>
