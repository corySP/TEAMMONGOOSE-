<!DOCTYPE html>
<html>

<!--
    cory sprague
    lm: 11/19/18
-->

<head>
	<title> WEASELCHAT </title>
        <meta charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="homepage.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
<!--	<script type="text/javascript" src="calendar_appear.js></script>
-->
	
</head>

<body> 	
	<script type="text/javascript">
	function popup(url) {
		popupWindow = window.open(url, 'popupWindow', 'height=500, width=600, left=10, top=10, resizable=yes, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=yes')
}
	</script>

	<div id="titlebar" class="titlebar">
		<a href="JavaScript:popup('/user_calendar_page.php')">WEASELCHAT</a>
		
	</div>	
	<hr />

	<div class="groupbar">
		<a href="homepage.php#group1">Group 1</a>	
		<a href="homepage.php#group2">Group 2</a>
		<a href="homepage.php#group3">Group 3</a>
	</div>
	
	<div class="main">
		<h2> Welcome to the weasel/mongoose file chat calendar scheduling website!
</h2>
	<!--remove the next line once chatbow is working-->
	
		
	</div>
	<div class="memberbar">
	</div>
	<div class="chatbar">
		<?php require_once('chatbox.php');?>
	</div>

</body>
</html>
