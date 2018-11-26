
<?php
/*======
   function: create_user_calendar_page: void -> void
   purpose: expect nothing, and returns nothing, BUT does
            expect the $_SESSION array to contain a key "username"
            with a valid Oracle username, and a key "password"
            with a valid Oracle password;
=====*/
function create_user_calendar_page()
{
?>
	<form method="post" action="<?= htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>">
		<fieldset>
			<legend> User Calendar Page: </legend>
            <input type="submit" name="user_log_out" value="Log Out" formnovalidate />
            <input type="submit" name="user_to_home" value="Home" formnovalidate />
            <input type="submit" name="user_to_files" value="Files" formnovalidate />
		</fieldset>
	</form>
<?php
	$username = strip_tags(htmlspecialchars($_SESSION['username']));
	$password = strip_tags(htmlspecialchars($_SESSION['password']));
	$conn = hsu_conn_sess($username, $password);

	$get_tasks_str = 'select project_name, task_name, task_date, current_status, 
							 task_description, user_comment
					  from   Project, Task, User
					  where  Task.user_id = :current_user
							 and Project.project_id = Task.project_id';
	$get_tasks_stmt = oci_parse($conn, $get_tasks_str);
	
	$current_user = $_SESSION["current_user"];
	oci_bind_by_name($get_tasks_stmt, ':current_user', $current_user);
	
	oci_execute($get_tasks_stmt, OCI_DEFAULT);
?>
	<table>
		<caption> Your Tasks: </caption>
		<tr>
			<th scope="col"> Project </th>
			<th scope="col"> Task </th>
			<th scope="col"> Date Due </th>
			<th scope="col"> Status </th>
			<th scope="col"> Description <th>
			<th scope="col"> Comment <th>
		</tr>
<?php
	while (oci_fetch($get_tasks_stmt))
	{
		$curr_project_name = oci_result($get_tasks_stmt, 'PROJECT_NAME');
		$curr_task_name = oci_result($get_tasks-stmt, 'TASK_NAME');
		$curr_task_date = oci_result($get_tasks_stmt, 'TASK_DATE');
		$curr_current_status = oci_result($get_tasks_stmt, 'CURRENT_STATUS');
		$curr_task_description = oci_result($get_tasks-stmt, 'TASK_DESCRIPTION');
		$curr_user_comment = oci_result($get_tasks_stmt, 'USER_COMMENT');
		
		if ($curr_current_status === NULL)
		{
			$curr_current_status = "none";
		}
		
		if ($curr_task_description === NULL)
		{
			$curr_task_description = "none";
		}
		
		if ($curr_user_comment === NULL)
		{
			$curr_user_comment = "none";
		}
?>
		<tr>
			<td> <?= $curr_project_name ?> </td>
			<td> <?= $curr_task_name ?> </td>
			<td> <?= $curr_task_date ?> </td>
			<td> <?= $curr_current_status ?> </td>
			<td> <?= $curr_task_description ?> </td>
			<td> <?= $curr_user_comment ?> </td>
		</tr>
<?php
	}
?>
	</table>
<?php
	oci_free_statement($get_tasks_stmt);
	
	$get_events_str = 'select event_name, event_datetime
					   from   Event, User, Event_users
					   where  Event_users.user_id = :current_user
							  and Event.event_id = Event_users.event_id';
	$get_events_stmt = oci_parse($conn, $get_events_str);
	
	oci_bind_by_name($get_events_stmt, ':current_user', $current_user);

	oci_execute($get_events_stmt, OCI_DEFAULT);
?>
	<table>
		<caption> Your Events: </caption>
		<tr>
			<th scope="col"> Event </th>
			<th scope="col"> Date and Time </th>
		</tr>
<?php
	while (oci_fetch($get_events_stmt))
	{
		$curr_event_name = oci_result($get_events_stmt, 'EVENT_NAME');
		$curr_event_datetime = oci_resutl($get_events_stmt, 'EVENT_DATETIME');
?>
		<tr>
			<td> <?= $curr_event_name ?> </td>
			<td> <?= $curr_event_datetime ?> </td>
		</tr>
<?php
	}
?>
	</table>
<?php
	oci_free_statement($get_events_stmt);
}
?>
</body>
</html>
