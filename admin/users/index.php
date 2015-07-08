<?php

require_once('../../common.php');
require_once('../../includes/profile.php');

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
		$profile = new Profile;
		
		if ($profile->create_account($_POST))
			$template->assign_var('SIDE_CONTENT', 'create_account_successful');
		else
		{
			$template->assign_vars(array('SIDE_CONTENT' => 'create_account_failed',
				'ERROR_MESSAGE' => $profile->error));
		}
	}
	else if (isset($_POST['action']) && $_POST['action'] == 'show_profile')
	{
		switch($_POST['searchType'])
		{
			case 'user':
				$stmnt = sprintf("SELECT * FROM profile INNER JOIN login ON profile.userid = login.id WHERE login.user = '%s'", $_POST['inputSearch']);

				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();

					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 1,
						'USER' => $row['user'],
						'FIRST' => $row['name'],
						'MIDDLE' => $row['middle'],
						'LAST' => $row['last'],
						'MAIDEN' => $row['maiden'],
						'ADDRESS1' => $row['address1'],
						'ADDRESS2' => $row['address2'],
						'CITY' => $row['city'],
						'STATE' => $row['state'],
						'ZIP' => $row['zip'],
						'COUNTRY' => $row['country'],
						'PHONE' => $row['phone'],
						'EMAIL' => $row['email']));
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
				break;
			case 'id':
				$stmnt = sprintf("SELECT * FROM profile INNER JOIN login ON profile.userid = login.id WHERE profile.id = %d", $_POST['inputSearch']);

				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();

					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 1,
						'USER' => $row['user'],
						'FIRST' => $row['name'],
						'MIDDLE' => $row['middle'],
						'LAST' => $row['last'],
						'MAIDEN' => $row['maiden'],
						'ADDRESS1' => $row['address1'],
						'ADDRESS2' => $row['address2'],
						'CITY' => $row['city'],
						'STATE' => $row['state'],
						'ZIP' => $row['zip'],
						'COUNTRY' => $row['country'],
						'PHONE' => $row['phone'],
						'EMAIL' => $row['email']));
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
				break;
			case 'first':
				$stmnt = sprintf("SELECT * FROM profile INNER JOIN login ON profile.userid = login.id WHERE profile.id = %d", $_POST['inputSearch']);

				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();

					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 1,
						'USER' => $row['user'],
						'FIRST' => $row['name'],
						'MIDDLE' => $row['middle'],
						'LAST' => $row['last'],
						'MAIDEN' => $row['maiden'],
						'ADDRESS1' => $row['address1'],
						'ADDRESS2' => $row['address2'],
						'CITY' => $row['city'],
						'STATE' => $row['state'],
						'ZIP' => $row['zip'],
						'COUNTRY' => $row['country'],
						'PHONE' => $row['phone'],
						'EMAIL' => $row['email']));
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
				break;
			case 'last':
				$stmnt = sprintf("SELECT * FROM profile INNER JOIN login ON profile.userid = login.id WHERE profile.last = '%s'", $_POST['inputSearch']);

				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();

					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 1,
						'USER' => $row['user'],
						'FIRST' => $row['name'],
						'MIDDLE' => $row['middle'],
						'LAST' => $row['last'],
						'MAIDEN' => $row['maiden'],
						'ADDRESS1' => $row['address1'],
						'ADDRESS2' => $row['address2'],
						'CITY' => $row['city'],
						'STATE' => $row['state'],
						'ZIP' => $row['zip'],
						'COUNTRY' => $row['country'],
						'PHONE' => $row['phone'],
						'EMAIL' => $row['email']));

				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
				break;
			case 'plate':
				// TODO: We need to include the vehicle table and relate it to the user profile
				/*$stmnt = sprintf("SELECT * FROM profile INNER JOIN login ON profile.userid = login.id WHERE profile.id = %d", $_POST['inputSearch']);

				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();

					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 1,
						'USER' => $row['user'],
						'FIRST' => $row['name'],
						'MIDDLE' => $row['middle'],
						'LAST' => $row['last'],
						'MAIDEN' => $row['maiden'],
						'ADDRESS1' => $row['address1'],
						'ADDRESS2' => $row['address2'],
						'CITY' => $row['city'],
						'STATE' => $row['state'],
						'ZIP' => $row['zip'],
						'COUNTRY' => $row['country'],
						'PHONE' => $row['phone'],
						'EMAIL' => $row['email']));

				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 2));
				}					
				$result->close();*/
				break;
			default:
				break;
		}
	}
	else if (isset($_POST['action']) && $_POST['action'] === 'unlock_user')
	{
		switch($_POST['searchType'])
		{
			case 'user':
				$stmnt = sprintf("SELECT disabled FROM login WHERE disabled = 1 and user = '%s'", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$stmnt = sprintf("UPDATE login SET disabled = 0 WHERE user = '%s'", $_POST['inputSearch']);

					if (!$user->sql_conn->query($stmnt))
						trigger_error('/admin/users/index.php: '.$user->sql_conn->error, E_USER_ERROR);

					$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 1));
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 2));
				$result->close();
				break;
			case 'id':
				$stmnt = sprintf("SELECT disabled FROM login INNER JOIN profile ON login.id = profile.userid WHERE disabled = 1 and profile.id = %d", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$stmnt = sprintf("UPDATE login SET disabled = 0 WHERE user = '%s'", $_POST['inputSearch']);

					if (!$user->sql_conn->query($stmnt))
						trigger_error('/admin/users/index.php: '.$user->sql_conn->error, E_USER_ERROR);

					$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 1));
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 2));
				$result->close();
				break;
			case 'first':
				break;
			case 'last':
				break;
			case 'plate':
				break;
			default:
				break;
		}
	}
	// Create an account side content
	else if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$template->assign_var('SIDE_CONTENT', 1);
	}
	// Modify profile side content
	else if (isset($_GET['option']) && $_GET['option'] == 2)
	{
		$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 0));
	}
	// Modify permissions side content
	else if (isset($_GET['option']) && $_GET['option'] == 3)
	{
		$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 0));
	}
	// Modify plans and services side content
	else if (isset($_GET['option']) && $_GET['option'] == 4)
	{
		$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 0));
	}
	// Modify vehicles side content
	else if (isset($_GET['option']) && $_GET['option'] == 5)
	{
		$template->assign_vars(array('SIDE_CONTENT' => 5, 'USERNAME_FOUND' => 0));
	}
	// Unlock user account side content
	else if (isset($_GET['option']) && $_GET['option'] == 6)
	{
		$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 0));
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

	$template->set_filenames(array('body' => 'admin_users.html'));

	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
