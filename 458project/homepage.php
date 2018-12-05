


<!--

<!DOCTYPE html>
<html>

    cory sprague
    lm: 11/19/18

<head>
    <title> WEASELCHAT </title>
        <meta charset="utf-8" />

	<link rel="stylesheet" type="text/css" href="homepage.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js
	
</head>

<body> 

-->	
	<script type="text/javascript">
	function popup(url) {
		popupWindow = window.open(url, 'popupWindow', 'height=500, width=600, left=10, top=10, resizable=yes, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=yes')
}
	</script>

	<div id="titlebar" class="titlebar">
        
        <a href="javascript:void(0)"><b>WEASEL PROJECT MANAGEMENT</b></a>
        <button id="slidePaneBtn" class="fbtn"><i class="fa fa-bars"></i> 
            Menu </button>
        <form method="post" action="<?= htmlentities($_SERVER['PHP_SELF'],
            ENT_QUOTES) ?>">
            <button id="logoutBtn" name="user_log_out">
                <i class="fa fa-power-off"></i> Logout
            </button>
        </form>
     </div>
    <!-- <hr /> -->




    <div id="groupbar">
<!--
        <button id="tasks">Tasks</button>
        <br />
        <button id="calendar">Calendar</button>
-->
<br />
        <a href="javascript:void(0)" id="tasks">YOUR TASKS</a>
<hr />
        <a href="javascript:void(0)" id="projLink">PROJECTS</a>
<!--
<hr />
        <a href="javascript:void(0)" id="calendarBtn">CALENDAR</a>
-->
<hr />
        <a href="JavaScript:popup('./user_calendar_page.php')" id="calendarBtn2">CALENDAR</a>
<hr />
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
    <br />
    <br />
    <div id="chatbar" class="chatbar">
        <?php require_once('chatbox.php');?>
    </div>

    <div id="footer">
        <button id="slideChatBtn" class="fbtn"><i class="fa fa-comment"></i> Chat</button>
    </div>

</body>
</html>
