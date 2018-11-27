
<?php

/*
	by cory sprague
	grabs the groups user list from the database
*/


function get_members()
{
	$username = strip_tags(htmlspecialchars($_SESSION['master_username']));
	$password = strip_tags(htmlspecialchars($_SESSION['master_password']));
	$conn = hsu_conn_sess($username, $password);

	$get_members_string = 'select ';

	$get_members_stmt = oci_parse($conn, $get_members_string);
	$current_user = $_SESSION["current_user"];
	oci_bind_by_name($get_members_stmt, ':current_user', $current_user);
	oci_execute($get_members_stmt, OCI_DEFAULT);

	while(oci_fetch($get_members_stmt))
	{
		
	}

	oci_free_statement($get_members_stmt);

?>
