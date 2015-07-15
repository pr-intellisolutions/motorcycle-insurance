<?php

require_once('../../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

if ($user->auth() && $user->role == 'admin')
{
	/*
	Order of execution on page load:

		-- GET method --
		No conditions:			# home content
		Account creation:		# create account content
		Modify profile:			# modify profile content
		Modify permissions:		# modify permissions content
		Modify plans/services:	# modify plans and services content
		Modify vehicles:		# modify vehicles content

		-- POST method --
		Process account creation:	# process account creation
		Process show profile:		# process show profile
		Process edit permissions:	# process edit permissions
		Process edit plans:			# process edit plans
		Process edit vehicles:		# process edit vehicles

	Note 1: This only controls what parts of the front-end are visible to the user at a time using
		    the back-end template engine.

	Note 2: The reason for having GET and POST methods is because GET method controls the presentation sequence
			and the POST method controls what is been processed in the background.
		  
	*/

	$template->assign_vars(array('SITE_URL' => SITE_URL,
		'FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST',
		'USERNAME' => $user->user));

	//# create account content
	if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$template->assign_var('SIDE_CONTENT', 1);
	}
	//# modify profile content
	else if (isset($_GET['option']) && $_GET['option'] == 2)
	{
		$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 0));
	}
	//# modify permissions content
	else if (isset($_GET['option']) && $_GET['option'] == 3)
	{
		$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 0));
	}
	//# modify plans and services content
	else if (isset($_GET['option']) && $_GET['option'] == 4)
	{
		$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 0));
	}
	//# modify vehicles content
	else if (isset($_GET['option']) && $_GET['option'] == 5)
	{
		$template->assign_vars(array('SIDE_CONTENT' => 5, 'USERNAME_FOUND' => 0));
	}
	//# process account creation
	else if (isset($_POST['action']) && $_POST['action'] === 'create_account')
	{
		if ($user->create_account($_POST))
			$template->assign_var('SIDE_CONTENT', 'create_account_successful');
		else
		{
			$template->assign_vars(array('SIDE_CONTENT' => 'create_account_failed',
				'ERROR_MESSAGE' => $user->error));
		}
	}
	//# process show profile
	else if (isset($_POST['action']) && $_POST['action'] === 'show_profile')
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
			case 'plate':
				break;
			default:
				break;
		}
	}
	//# process edit permissions
	else if (isset($_POST['action']) && $_POST['action'] === 'edit_permissions')
	{
		switch($_POST['searchType'])
		{
			case 'user':
				$stmnt = sprintf("SELECT * FROM login WHERE user = '%s'", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					
					if ($row['role'] === 'user')
					{
						$template->assign_vars(array('USER_CHG_PERM_ALLOW' => false, 'USER' => $row['user']));
					}
					else
					{
						$template->assign_vars(array('USER_CHG_PERM_ALLOW' => true, 'USER' => $row['user']));

						if (strstr($row['permissions'], 'all'))
						{
							$template->assign_vars(array('CHECK_ALL' => 'checked',
								'CHECK_U' => 'disabled',
								'CHECK_S' => 'disabled',
								'CHECK_O' => 'disabled',
								'CHECK_R' => 'disabled',
								'CHECK_F' => 'disabled'));
						}
					}
					$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 1));
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 2));
				$result->close();
				break;
			case 'id':
				$stmnt = sprintf("SELECT * FROM login INNER JOIN profile ON login.id = profile.userid WHERE profile.id = %d", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					
					if ($row['role'] === 'user')
					{
						$template->assign_vars(array('USER_CHG_PERM_ALLOW' => false, 'USER' => $row['user']));

					}
					else
					{
						$template->assign_vars(array('USER_CHG_PERM_ALLOW' => true, 'USER' => $row['user']));
					}
					$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 1));
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 2));
				$result->close();
				break;
			case 'plate':
				break;
			default:
				break;
		}
	}
	//# process edit plans
	else if (isset($_POST['action']) && $_POST['action'] === 'edit_plans')
	{
		
	}
	//# process edit vehicles
	else if (isset($_POST['action']) && $_POST['action'] === 'edit_vehicles')
	{
		
	}
	//# home content
	else
	{
		$template->assign_var('SIDE_CONTENT', 'home');

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
