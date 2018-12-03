<?php
    session_start();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<!--
  by: Chadwick Davis
     last modified: 22-NOV-18

     requires:
     *	328footer.html
     *  create_login.php
     *	hsu_conn_sess.php
     *	destroy_and_exit.php

     you can run this using the URL:
http://nrs-projects.humboldt.edu/~cjd10/458project/TEAMMONGOOSE-/458project/projectSession.php
-->

<head>
    <title> WEASELCHAT </title>
        <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="homepage.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js" defer="defer"></script>
    <script type="text/javascript" src="https:code.jquery.com/ui/1.12.1/jquery-ui.js" defer="defer"></script>
    <script type="text/javascript" src="task.js" defer="defer"></script>
    <script src="chat.js" type="text/javascript" defer="defer"></script>
    <script src="buCal.js" type="text/javascript" defer="defer"></script>
    <script src="slides.js" type="text/javascript" defer="defer"></script>

    <?php
    require_once("create_hsu_login.php");
    require_once("create_login.php");
    require_once("register.php");
    require_once("register_confirmation.php");
    require_once("user_home_page.php");
    require_once("user_calendar_page.php");
    require_once("user_file_page.php");
    require_once("create_test.php");
    require_once("hsu_conn_sess.php");
    require_once("destroy_and_exit.php");
    ?>

   
</head>

<body>
   <!-- <h1> Team Project Manager, CS 458 </h1> -->

    <?php
    //When you are starting Brings up HSU master login
    if ( (! array_key_exists('next-stage', $_SESSION)) ||
         (($_SESSION['next-stage'] == "user_loginged_in") &&
	  (array_key_exists('user_log_out', $_POST))))
       {
           //Creates Loogin form
           create_login();
	   $_SESSION['next-stage'] = "login_options";
       }
  /*
  //When you are returning to user_login from either the
    // user registration or from the user_home_page
    elseif (($_SESSION['next-stage'] == "user_login")||
            (($_SESSION['next-stage'] == "user_logged_in") &&
    	   (array_key_exists('user_log_out', $_POST))))
       {
	   //Creates Form for user_login
	   create_login();
       	   $_SESSION['next-stage'] = "login_options";
       }
    */  
    //when you are going to register from user_login
    elseif (($_SESSION['next-stage'] == "login_options") &&
           (array_key_exists('register-button', $_POST)))
       {
           ///Creates register form
	   show_register();
           $_SESSION['next-stage'] = "register_confirmation";
       }
   //when you are returning to the user login from the user registration
   elseif (($_SESSION['next-stage'] == "register_confirmation") &&
           (array_key_exists('register-back', $_POST)) )
       {
           //Creates Form for user_login
	   create_login();
       	   $_SESSION['next-stage'] = "login_options";
       }
     

    //when you are going to register confirmation from user_login
    elseif (($_SESSION['next-stage'] == "register_confirmation") &&
           (array_key_exists('register-submit', $_POST)))
       {
           ///Creates register confirmation form
	   create_register_confirmation();
           $_SESSION['next-stage'] = "user_login";
       }
     
    //When you are going to the home page from any other user page
    //or the login page.
    elseif ((($_SESSION['next-stage'] == "login_options") &&
    	   (array_key_exists('login-submit-button', $_POST))) ||
	   (($_SESSION['next-stage'] == "user_logged_in") &&
	   (array_key_exists('user_to_home', $_POST))))
       //))
       {
           ///Generates user home page
	   create_user_home_page();
	   $_SESSION['next-stage'] = "user_logged_in";
       }


    //when you are going to user calendar page from either the home page or the file page
    elseif (($_SESSION['next-stage'] == "user_logged_in") &&
    	   (array_key_exists('user_to_calendar', $_POST)))
       {
           ///Generates user celendar page
	   create_user_calendar_page();
	   $_SESSION['next-stage'] = "user_logged_in";
       }

   //when you are going to user file page from either the home page or the file page
    elseif (($_SESSION['next-stage'] == "user_logged_in") &&
    	   (array_key_exists('user_to_files', $_POST)))
       {
           ///Generates user file page
	   create_user_file_page();
	   $_SESSION['next-stage'] = "user_logged_in";
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

        $_SESSION['next-stage'] = "login_options";
    }

     
   // require_once("328footer.html");
    ?>

</body>
</html>
