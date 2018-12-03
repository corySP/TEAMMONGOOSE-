<!--
<!DOCTYPE html>
<html>

    cory sprague
    lm: 11/19/18

<head>
    <title> WEASELCHAT </title>
        <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="homepage.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="calendar_appear.js></script>
    
</head>

<body>  
-->
    <script type="text/javascript">
    function popup(url) {
        popupWindow = window.open(url, 'popupWindow', 'height=500, width=600, left=10, top=10, resizable=yes, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=yes')
}
    </script>

    <div id="titlebar" class="titlebar">
        <!--<a href="JavaScript:popup('./user_calendar_page.php')">WEASELCHAT</a>-->
        <a href="javascript:void(0)"><b>WEASEL PROJECT MANAGEMENT</b></a>
        <button id="slidePaneBtn">Nav</button>
    </div>  
    <!-- <hr /> -->

<!--
    <p> <?php echo $_SESSION['current_user']; ?> </p>
-->

    <div id="groupbar">
<!--
        <button id="tasks">Tasks</button>
        <br />
        <button id="calendar">Calendar</button>
-->
        <a href="javascript:void(0)" id="tasks">PROJECT</a>
<hr />
        <a href="javascript:void(0)" id="calendar">CALENDAR</a>
<!--
        <a href="homepage.php#group2">Group 2</a>
        <a href="homepage.php#group3">Group 3</a>
-->
    </div>
     <div id="contents"></div>

<!--
    <div class="memberbar">
-->

    </div>
    <div id="chatbar" class="chatbar">
        <?php require_once('chatbox.php');?>
    </div>

    <div id="footer">
        <button id="slideChatBtn">Chat</button>
    </div>
<!--
</body>
</html>
-->
