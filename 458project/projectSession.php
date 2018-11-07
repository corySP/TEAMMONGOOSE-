<?php
    session_start();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<!--
  by: Chadwick Davis
     last modified: 1-NOV-18

     requires:
     *	328footer.html
     *  create_login.php
     *	hsu_conn_sess.php
     *	destroy_and_exit.php

     you can run this using the URL:
http://nrs-projects.humboldt.edu/~cjd10/458project/projectSession.php
-->

<head>
    <title> Team Project Manager </title>
    <meta charset="utf-8" />

    <?php
    require_once("create_login.php");
    require_once("create_test.php");
    require_once("hsu_conn_sess.php");
    require_once("destroy_and_exit.php");
    ?>

   
</head>

<body>
    <h1> Team Project Manager, CS 458 </h1>

    <?php
    //When you are starting
    if ((! array_key_exists('next-stage', $_SESSION)))
       {
           //Creates Loogin form
           create_login();
	   $_SESSION['next-stage'] = "test_data";
       }
    elseif ($_SESSION['next-stage'] == "test_data")
       {
	   //Creates Form to test data in database
	   create_test();
       	   
	   session_destroy();
	   session_regenerate_id(TRUE);
	   session_start();

	   create_login();
	   $_SESSION['next-stage'] = "create_login";
       }
    

    else
    {
      ?>
        <p> <strong> UH-OH!You should NOT have been able to reach
            here! </strong> </p>
      <?php

        session_destroy();
        session_regenerate_id(TRUE);
        session_start();

         create_login();
        
    }

     
    require_once("328footer.html");
    ?>

</body>
</html>
