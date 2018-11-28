
<?php

/*
	by cory sprague
	grabs the users group list from the database
*/


function get_groups()
{
	$username = strip_tags(htmlspecialchars($_SESSION['master_username']));
	$password = strip_tags(htmlspecialchars($_SESSION['master_password']));
	$conn = hsu_conn_sess($username, $password);

	$get_groups_string = 'select ';

	$get_groups_stmt = oci_parse($conn, $get_groups_string);
	$current_user = $_SESSION["current_user"];
	oci_bind_by_name($get_groups_stmt, ':current_user', $current_user);
	oci_execute($get_groups_stmt, OCI_DEFAULT);

	while(oci_fetch($get_groups_stmt))
	{
		
	}

	oci_free_statement($get_groups_stmt);

?>
