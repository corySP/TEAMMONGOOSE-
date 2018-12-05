<?php
    session_start();
?>

<!DOCTYPE html>
<!--<html xmlns="http://www.w3.org/1999/xhtml" ng-app="myApp" ng-controller="AppCtrl">-->
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
    <title> MONGOOSE MANAGER </title>
        <meta charset="utf-8" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="homepage.css" />
    <!--<link href="calendar.css" type="text/css" rel="stylesheet" />-->

    <!--<script type="text/javascript" src="jquery-3.3.1.min.js" defer="defer"></script>-->
    <script defer="defer" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https:code.jquery.com/ui/1.12.1/jquery-ui.js" defer="defer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.8/angular.min.js" type="text/javascript" defer="defer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js" defer="defer"></script>
    <script type="text/javascript" src="task.js" defer="defer"></script>
    <script type="text/javascript" src="calendar.js" defer="defer"></script>
    <script src="chat.js" type="text/javascript" defer="defer"></script>
    <script src="cal.js" type="text/javascript" defer="defer"></script>
    <script src="slides.js" type="text/javascript" defer="defer"></script>
    <script src="project.js" type="text/javascript" defer="defer"></script>

    <?php
    require_once("create_login.php");
    require_once("register.php");
    require_once("register_confirmation.php");
    require_once("user_home_page.php");
    require_once("hsu_conn_sess.php");
    require_once("destroy_and_exit.php");
    ?>

</head>

<body>

   <!-- <h1> Team Project Manager, CS 458 </h1> -->

    <?php
    if ( (array_key_exists('current_user', $_SESSION) && 
        $_SESSION['current_user'] >= 0 ) &&
        (!array_key_exists('user_log_out', $_POST)))
    {
        create_user_home_page();
    } else
    //When you are starting Brings up HSU master login
    if ( (! array_key_exists('next-stage', $_SESSION)) ||
         (($_SESSION['next-stage'] == "user_logged_in") &&
	  (array_key_exists('user_log_out', $_POST))))
    {
        session_destroy();
        session_regenerate_id(TRUE);
        session_start();
?>
    <div id="titlebar" class="titlebar">
        <!--<a href="JavaScript:popup('./user_calendar_page.php')">WEASELCHAT</a>-->
        <a href="javascript:void(0)"><b>WEASEL PROJECT MANAGEMENT</b></a>
    </div><div id="contents2">
<?php
        //Creates Loogin form
        create_login();
        $_SESSION['next-stage'] = "login_options";
?>
    </div>
<?php
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
?>
    <div id="titlebar" class="titlebar">
        <!--<a href="JavaScript:popup('./user_calendar_page.php')">WEASELCHAT</a>-->
        <a href="javascript:void(0)"><b>WEASEL PROJECT MANAGEMENT</b></a>
    </div><div id="contents2">
<?php
           ///Creates register form
           show_register();
           $_SESSION['next-stage'] = "register_confirmation";
?>
    </div>
<?php
       }
   //when you are returning to the user login from the user registration
   elseif (($_SESSION['next-stage'] == "register_confirmation") &&
           (array_key_exists('register-back', $_POST)) )
       {
?>
    <div id="titlebar" class="titlebar">
        <!--<a href="JavaScript:popup('./user_calendar_page.php')">WEASELCHAT</a>-->
        <a href="javascript:void(0)"><b>WEASEL PROJECT MANAGEMENT</b></a>
    </div><div id="contents2">
<?php
           //Creates Form for user_login
           create_login();
       	   $_SESSION['next-stage'] = "login_options";
?>
    </div>
<?php
       }
     

    //when you are going to register confirmation from user_login
    elseif (($_SESSION['next-stage'] == "register_confirmation") &&
           (array_key_exists('register-submit', $_POST)))
       {
?>
    <div id="titlebar" class="titlebar">
        <!--<a href="JavaScript:popup('./user_calendar_page.php')">WEASELCHAT</a>-->
        <a href="javascript:void(0)"><b>WEASEL PROJECT MANAGEMENT</b></a>
    </div><div id="contents2">
<?php
           ///Creates register confirmation form
           create_register_confirmation();
           $_SESSION['next-stage'] = "user_login";
?>
    </div>
<?php
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
    else
    {
?>
    <div id="titlebar" class="titlebar">
        <!--<a href="JavaScript:popup('./user_calendar_page.php')">WEASELCHAT</a>-->
        <a href="javascript:void(0)"><b>WEASEL PROJECT MANAGEMENT</b></a>
    </div><div id="contents2">
<?php
        /*
      ?>
        <p> <strong> UH-OH!You should NOT have been able to reach
            here! </strong> </p>
      <?php
         */

        session_destroy();
        session_regenerate_id(TRUE);
        session_start();

         create_login();

        $_SESSION['next-stage'] = "login_options";
?>
    </div>
<?php
    }

     
   // require_once("328footer.html");
    ?>

</body>
</html>
