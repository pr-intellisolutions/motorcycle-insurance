<?php

require_once('../../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth() && $user->role == 'admin')
{
	$template->assign_vars(array('SIDE_CONTENT' => 'home',
		'FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST',
		'USERNAME' => $user->user));

	// Process account creation
	if (isset($_POST['action']) && $_POST['action'] == 'create_account')
	{
		$template->assign_var('SIDE_CONTENT', 'create_account_status');
	}
	// Create an account side content
	else if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$template->assign_var('SIDE_CONTENT', 1);
	}
	// Modify profile side content
	else if (isset($_GET['option']) && $_GET['option'] == 2)
	{
		$template->assign_var('SIDE_CONTENT', 2);
	}
	// Modify permissions side content
	else if (isset($_GET['option']) && $_GET['option'] == 3)
	{
		$template->assign_var('SIDE_CONTENT', 3);
	}
	// Modify plans and services side content
	else if (isset($_GET['option']) && $_GET['option'] == 4)
	{
		$template->assign_var('SIDE_CONTENT', 4);
	}
	// Modify vehicles side content
	else if (isset($_GET['option']) && $_GET['option'] == 5)
	{
		$template->assign_var('SIDE_CONTENT', 5);
	}
	// Unlock user account side content
	else if (isset($_GET['option']) && $_GET['option'] == 6)
	{
		$template->assign_var('SIDE_CONTENT', 6);
	}
	// Information for home content
	else
	{
		/* Fetch all registered users to show on the modal box */
		$stmnt = sprintf("SELECT id, user, regdate, lastvisit FROM login");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;
			
				$template->assign_block_vars('user_reg_list',
					array('INDEX' => $index, 'ID' => $row['id'], 'USERNAME' => $row['user'], 'REG_DATE' => $row['regdate'], 'LAST_VISIT' => $row['lastvisit']));
			}
			$template->assign_vars(array('NUM_REG_USERS' => $index, 'NO_REG_RESULTS' => false));
		}
		else
			$template->assign_vars(array('NUM_REG_USERS' => 0, 'NO_REG_RESULTS' => true));

		$result->close();

		/* Fetch all active users to show on the modal box */
		$stmnt = sprintf("SELECT user, regdate, lastvisit FROM login where active=1");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;
			
				$template->assign_block_vars('user_active_list',
					array('INDEX' => $index, 'USERNAME' => $row['user'], 'REG_DATE' => $row['regdate'], 'LAST_VISIT' => $row['lastvisit']));
			}
			$template->assign_vars(array('NUM_ACTIVE_USERS' => $index, 'NO_ACTIVE_RESULTS' => false));
		}
		else
			$template->assign_vars(array('NUM_ACTIVE_USERS' => 0, 'NO_ACTIVE_RESULTS' => true));

		$result->close();

		/* Fetch all inactive users to show on the modal box */
		$stmnt = sprintf("SELECT user, regdate, lastvisit FROM login where active=0");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;
			
				$template->assign_block_vars('user_inactive_list',
					array('INDEX' => $index, 'USERNAME' => $row['user'], 'REG_DATE' => $row['regdate'], 'LAST_VISIT' => $row['lastvisit']));
			}
			$template->assign_vars(array('NUM_INACTIVE_USERS' => $index, 'NO_INACTIVE_RESULTS' => false));
		}
		else
			$template->assign_vars(array('NUM_INACTIVE_USERS' => 0, 'NO_INACTIVE_RESULTS' => true));

		$result->close();

		/* Fetch all disabled users to show on the modal box */
		$stmnt = sprintf("SELECT user, regdate, lastvisit FROM login where disabled=1");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;
			
				$template->assign_block_vars('user_disabled_list',
					array('INDEX' => $index, 'USERNAME' => $row['user'], 'REG_DATE' => $row['regdate'], 'LAST_VISIT' => $row['lastvisit']));
			}
			$template->assign_vars(array('NUM_DISABLED_USERS' => $index, 'NO_DISABLED_RESULTS' => false));
		}
		else
			$template->assign_vars(array('NUM_DISABLED_USERS' => 0, 'NO_DISABLED_RESULTS' => true));

		$result->close();

		/* Fetch all expired users to show on the modal box */
		$stmnt = sprintf("SELECT user, regdate, lastvisit FROM login where expired=1");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;
			
				$template->assign_block_vars('user_expired_list',
					array('INDEX' => $index, 'USERNAME' => $row['user'], 'REG_DATE' => $row['regdate'], 'LAST_VISIT' => $row['lastvisit']));
			}
			$template->assign_vars(array('NUM_EXPIRED_USERS' => $index, 'NO_EXPIRED_RESULTS' => false));
		}
		else
			$template->assign_vars(array('NUM_EXPIRED_USERS' => 0, 'NO_EXPIRED_RESULTS' => true));

		$result->close();
	}

	$template->set_filenames(array('body' => 'admin_user.html'));

	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
