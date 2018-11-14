<?php
    session_start();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<!--
by: Mabel Houle
last modified: 2018-11-12
URL: http://nrs-projects.humboldt.edu/~mfh128/cs458/mabelcontrol.php

A portion of the site containing only the pages I am currently working on,
for testing purposes.
-->

<head>
    <title> Register </title>
    <meta charset="utf-8" />

    <?php
        require_once("register.php");
        require_once("confirm.php");
        require_once("dummy_login.php");
    ?>
</head>

<body>

<?php
if (!array_key_exists("reg", $_POST))
// replace with being sent here from login page
{
    show_register();
    $_SESSION['next-stage'] = "confirm";
}
elseif ($_SESSION['next-stage'] == "confirm")
{
    show_confirm();
    $_SESSION['next-stage'] = "login";
}
elseif ($_SESSION['next-stage'] == "login")
{
    // change to actual login
    dummy_login();

    // change
    session_destroy();
}
else
{
?>
    <p>
        An error has occurred.
    </p>

<?php
    session_destroy();
    session_regenerate_id(TRUE);
    session_start();

    // replace with login
    show_register();

    // replace
    $_SESSION['next-stage'] = "confirm";
}
?>

</body>
</html>