<?php

require_once('../../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

if ($user->auth() && $user->role === 'admin')
{
	/*
	Order of execution on page load:

		-- GET method --
		No conditions:			# home content
		Account creation:		# create account content
		Account deletion:		# remove account content
		Modify profile:			# modify profile content
		Modify permissions:		# modify permissions content
		Modify plans/services:	# modify plans and services content
		Modify vehicles:		# modify vehicles content

		-- POST method --
		Process show profile:		# process show profile
		Process edit permissions:	# process edit permissions
		Process show plans:			# process edit plans
		Process show vehicles:		# process edit vehicles

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
	//# remove account content
	else if (isset($_GET['option']) && $_GET['option'] == 2)
	{
		$stmnt = sprintf("SELECT profile.id, user, email, phone FROM login INNER JOIN profile on login.id=profile.userid ORDER BY profile.id");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;

				$template->assign_block_vars('user_list',
					array('MEMBERID' => $row['id'],
						'USERNAME' => $row['user'],
						'EMAIL' => $row['email'],
						'PHONE' => $row['phone']));
			}
			$result->close();
		}
		$template->assign_var('SIDE_CONTENT', 2);
	}
	//# modify profile content
	else if (isset($_GET['option']) && $_GET['option'] == 3)
	{
		if (isset($_GET['show']) && $_GET['show'] == true)
		{
			$stmnt = sprintf("SELECT * FROM profile INNER JOIN login ON profile.userid = login.id WHERE login.user = '%s'", $_GET['user']);

			$result = $user->sql_conn->query($stmnt);
			
			if ($result->num_rows > 0)
			{
				$row = $result->fetch_assoc();

				$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 1,
					'USER' => $row['user'],
					'FIRST' => $row['first'],
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

					$result->close();
			}
		}
		else
		{
			$stmnt = sprintf("SELECT profile.id, user, email, phone FROM login INNER JOIN profile on login.id=profile.userid ORDER BY profile.id");

			$result = $user->sql_conn->query($stmnt);
			
			if ($result->num_rows > 0)
			{
				$index = 0;
				while ($row = $result->fetch_assoc())
				{
					$index++;

					$template->assign_block_vars('user_list',
						array('MEMBERID' => $row['id'],
							'USERNAME' => $row['user'],
							'EMAIL' => $row['email'],
							'PHONE' => $row['phone']));
				}
				$result->close();
			}
			$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 0));
		}
	}
	//# modify permissions content
	else if (isset($_GET['option']) && $_GET['option'] == 4)
	{
		$stmnt = sprintf("SELECT profile.id, user, email, phone FROM login INNER JOIN profile on login.id=profile.userid ORDER BY profile.id");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;

				$template->assign_block_vars('user_list',
					array('MEMBERID' => $row['id'],
						'USERNAME' => $row['user'],
						'EMAIL' => $row['email'],
						'PHONE' => $row['phone']));
			}
			$result->close();
		}
		$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 0));
	}
	//# modify plans and services content
	else if (isset($_GET['option']) && $_GET['option'] == 5)
	{
		$stmnt = sprintf("SELECT profile.id, user, email, phone FROM login INNER JOIN profile on login.id=profile.userid ORDER BY profile.id");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;

				$template->assign_block_vars('user_list',
					array('MEMBERID' => $row['id'],
						'USERNAME' => $row['user'],
						'EMAIL' => $row['email'],
						'PHONE' => $row['phone']));
			}
			$result->close();
		}
		$template->assign_vars(array('SIDE_CONTENT' => 5, 'USERNAME_FOUND' => 0));
	}
	//# modify vehicles content
	else if (isset($_GET['option']) && $_GET['option'] == 6)
	{
		$stmnt = sprintf("SELECT profile.id, user, email, phone FROM login INNER JOIN profile on login.id=profile.userid ORDER BY profile.id");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;

				$template->assign_block_vars('user_list',
					array('MEMBERID' => $row['id'],
						'USERNAME' => $row['user'],
						'EMAIL' => $row['email'],
						'PHONE' => $row['phone']));
			}
			$result->close();
		}
		$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 0));
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

					$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 1,
						'USER' => $row['user'],
						'FIRST' => $row['first'],
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

					$result->close();
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 2));
				break;
			case 'id':
				$stmnt = sprintf("SELECT * FROM profile INNER JOIN login ON profile.userid = login.id WHERE profile.id = %d", $_POST['inputSearch']);

				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();

					$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 1,
						'USER' => $row['user'],
						'FIRST' => $row['first'],
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

					$result->close();
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 2));
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

						if (!strstr($row['permissions'], 'none'))
						{
							if (strstr($row['permissions'], 'all'))
							{
								$template->assign_vars(array('CHECK_ALL' => 'checked',
									'CHECK_U' => 'disabled',
									'CHECK_S' => 'disabled',
									'CHECK_O' => 'disabled',
									'CHECK_R' => 'disabled',
									'CHECK_F' => 'disabled'));
							}
							else
							{
								if (strstr($row['permissions'], 'u')) $template->assign_var('CHECK_U', 'checked');
								if (strstr($row['permissions'], 's')) $template->assign_var('CHECK_S', 'checked');
								if (strstr($row['permissions'], 'o')) $template->assign_var('CHECK_O', 'checked');
								if (strstr($row['permissions'], 'r')) $template->assign_var('CHECK_R', 'checked');
								if (strstr($row['permissions'], 'f')) $template->assign_var('CHECK_F', 'checked');
							}
						}
					}
					$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 1));
					
					$result->close();
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 2));
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

						if (!strstr($row['permissions'], 'none'))
						{
							if (strstr($row['permissions'], 'all'))
							{
								$template->assign_vars(array('CHECK_ALL' => 'checked',
									'CHECK_U' => 'disabled',
									'CHECK_S' => 'disabled',
									'CHECK_O' => 'disabled',
									'CHECK_R' => 'disabled',
									'CHECK_F' => 'disabled'));
							}
							else
							{
								if (strstr($row['permissions'], 'u')) $template->assign_var('CHECK_U', 'checked');
								if (strstr($row['permissions'], 's')) $template->assign_var('CHECK_S', 'checked');
								if (strstr($row['permissions'], 'o')) $template->assign_var('CHECK_O', 'checked');
								if (strstr($row['permissions'], 'r')) $template->assign_var('CHECK_R', 'checked');
								if (strstr($row['permissions'], 'f')) $template->assign_var('CHECK_F', 'checked');
							}
						}
					}
					$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 1));

					$result->close();
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 2));
				break;
		}
	}
	//# process show plans
	else if (isset($_POST['action']) && $_POST['action'] === 'show_plans')
	{
		switch($_POST['searchType'])
		{
			case 'user':
				$username = $user->sanitize_input($_POST['inputSearch']);

				if ($user->user_valid($username))
				{
					$stmnt = sprintf("SELECT * FROM login, services, plans WHERE login.id=services.userid and plans.id=services.plan_id and login.user='%s'", $username);
					
					$result = $user->sql_conn->query($stmnt);
					
					if ($result->num_rows > 0)
					{
						$template->assign_var('USER', $username);

						$index = 0;
	
						while ($row = $result->fetch_assoc())
						{
							$index++;
				
							$template->assign_block_vars('user_plans', array(
								'INDEX' => $index,
								'PLAN_NAME' => $row['title'],
								'OCCUR_CURR' => $row['occurrence_counter'],
								'OCCUR_MAX' => $row['num_occurrences'],
								'MILE_CURR' => $row['miles_counter'],
								'MILE_MAX' => $row['num_miles'],
								'PLAN_EXP' => $row['exp_date']));
						}
						$template->assign_vars(array('SIDE_CONTENT' => 5, 'USERNAME_FOUND' => 1, 'PLANS_FOUND' => true));
					}
					else
						$template->assign_vars(array('SIDE_CONTENT' => 5, 'USERNAME_FOUND' => 1, 'PLANS_FOUND' => false));

					$result->close();
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 5, 'USERNAME_FOUND' => 2));
				break;
			case 'id':
				$member_id = $user->sanitize_input($_POST['inputSearch']);
				
				$result = $user->sql_conn->query("SELECT user FROM login INNER JOIN profile ON login.id=profile.userid WHERE profile.id=$member_id");

				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$stmnt = sprintf("SELECT * FROM login, services, plans WHERE login.id=services.userid and plans.id=services.plan_id and login.user='%s'", $row['user']);
					$template->assign_var('USER', $row['user']);
					$result->close();

					$result = $user->sql_conn->query($stmnt);
					
					if ($result->num_rows > 0)
					{
						$index = 0;
	
						while ($row = $result->fetch_assoc())
						{
							$index++;
				
							$template->assign_block_vars('user_plans', array(
								'INDEX' => $index,
								'PLAN_NAME' => $row['title'],
								'OCCUR_CURR' => $row['occurrence_counter'],
								'OCCUR_MAX' => $row['num_occurrences'],
								'MILE_CURR' => $row['miles_counter'],
								'MILE_MAX' => $row['num_miles'],
								'PLAN_EXP' => $row['exp_date']));
						}
						$template->assign_vars(array('SIDE_CONTENT' => 5, 'USERNAME_FOUND' => 1, 'PLANS_FOUND' => true));

						$result->close();
					}
					else
						$template->assign_vars(array('SIDE_CONTENT' => 5, 'USERNAME_FOUND' => 1, 'PLANS_FOUND' => false));
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 5, 'USERNAME_FOUND' => 2));
				break;
		}
	}
	//# process show vehicles
	else if (isset($_POST['action']) && $_POST['action'] === 'show_vehicles')
	{
		switch($_POST['searchType'])
		{
			case 'user':
				$username = $user->sanitize_input($_POST['inputSearch']);

				if ($user->user_valid($username))
				{
					$stmnt = sprintf("SELECT * FROM login, services, vehicles, plans WHERE login.id=services.userid and services.id=vehicles.service_id and services.plan_id=plans.id and login.user='%s'", $username);
					
					$result = $user->sql_conn->query($stmnt);
					
					if ($result->num_rows > 0)
					{
						$template->assign_var('USER', $username);

	
						while ($row = $result->fetch_assoc())
						{
							$template->assign_block_vars('vehicle_list', array(
								'TYPE' => $row['type'],
								'MODEL' => $row['model'],
								'BRAND' => $row['brand'],
								'YEAR' => $row['year'],
								'PLATE' => $row['plate'],
								'SERIAL' => $row['serial'],
								'SERVICE_PLAN' => $row['title']));
						}
						$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 1));

						$result->close();
					}
					else
						$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 3));
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 2));
				break;
			case 'id':
				$member_id = $user->sanitize_input($_POST['inputSearch']);
				
				$result = $user->sql_conn->query("SELECT user FROM login INNER JOIN profile ON login.id=profile.userid WHERE profile.id=$member_id");

				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$stmnt = sprintf("SELECT * FROM login, services, vehicles, plans WHERE login.id=services.userid and services.id=vehicles.service_id and services.plan_id=plans.id and login.user='%s'", $row['user']);
					$template->assign_var('USER', $row['user']);
					$result->close();
					
					$result = $user->sql_conn->query($stmnt);
					
					if ($result->num_rows > 0)
					{	
						while ($row = $result->fetch_assoc())
						{
							$template->assign_block_vars('vehicle_list', array(
								'TYPE' => $row['type'],
								'MODEL' => $row['model'],
								'BRAND' => $row['brand'],
								'YEAR' => $row['year'],
								'PLATE' => $row['plate'],
								'SERIAL' => $row['serial'],
								'SERVICE_PLAN' => $row['title']));
						}
						$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 1));

						$result->close();
					}
					else
						$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 3));
				}
				else
					$template->assign_vars(array('SIDE_CONTENT' => 6, 'USERNAME_FOUND' => 2));
				break;
		}
	}
	//# home content
	else
	{
		$template->assign_var('SIDE_CONTENT', 'home');

		/* Fetch all registered users to show on the modal box */
		$stmnt = sprintf("SELECT profile.id, user, email, phone, CONCAT (first, ' ', middle, ' ', last, ' ', maiden) as fullname FROM login INNER JOIN profile on login.id=profile.userid ORDER BY profile.id");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;

				$template->assign_block_vars('user_reg_list',
					array('MEMBERID' => $row['id'],
						'USERNAME' => $row['user'],
						'FULLNAME' => $row['fullname'],
						'EMAIL' => $row['email'],
						'PHONE' => $row['phone']));
			}
			$template->assign_vars(array('NUM_REG_USERS' => $index, 'NO_REG_RESULTS' => false));

			$result->close();
		}
		else
			$template->assign_vars(array('NUM_REG_USERS' => 0, 'NO_REG_RESULTS' => true));

		/* Fetch all active users to show on the modal box */
		$stmnt = sprintf("SELECT profile.id, user, email, phone, CONCAT (first, ' ', middle, ' ', last, ' ', maiden) as fullname FROM login INNER JOIN profile on login.id=profile.userid WHERE active=1 ORDER BY profile.id");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;
			
				$template->assign_block_vars('user_active_list',
					array('MEMBERID' => $row['id'],
						'USERNAME' => $row['user'],
						'FULLNAME' => $row['fullname'],
						'EMAIL' => $row['email'],
						'PHONE' => $row['phone']));
			}
			$template->assign_vars(array('NUM_ACTIVE_USERS' => $index, 'NO_ACTIVE_RESULTS' => false));

			$result->close();
		}
		else
			$template->assign_vars(array('NUM_ACTIVE_USERS' => 0, 'NO_ACTIVE_RESULTS' => true));

		/* Fetch all inactive users to show on the modal box */
		$stmnt = sprintf("SELECT profile.id, user, email, phone, CONCAT (first, ' ', middle, ' ', last, ' ', maiden) as fullname FROM login INNER JOIN profile on login.id=profile.userid WHERE active=0 ORDER BY profile.id");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;

				$template->assign_block_vars('user_inactive_list',
					array('MEMBERID' => $row['id'],
						'USERNAME' => $row['user'],
						'FULLNAME' => $row['fullname'],
						'EMAIL' => $row['email'],
						'PHONE' => $row['phone']));
			}
			$template->assign_vars(array('NUM_INACTIVE_USERS' => $index, 'NO_INACTIVE_RESULTS' => false));

			$result->close();
		}
		else
			$template->assign_vars(array('NUM_INACTIVE_USERS' => 0, 'NO_INACTIVE_RESULTS' => true));

		/* Fetch all disabled users to show on the modal box */
		$stmnt = sprintf("SELECT profile.id, user, email, phone, CONCAT (first, ' ', middle, ' ', last, ' ', maiden) as fullname FROM login INNER JOIN profile on login.id=profile.userid WHERE disabled=1 ORDER BY profile.id");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;
	
				$template->assign_block_vars('user_disabled_list',
					array('MEMBERID' => $row['id'],
						'USERNAME' => $row['user'],
						'FULLNAME' => $row['fullname'],
						'EMAIL' => $row['email'],
						'PHONE' => $row['phone']));
	
			}
			$template->assign_vars(array('NUM_DISABLED_USERS' => $index, 'NO_DISABLED_RESULTS' => false));

			$result->close();
		}
		else
			$template->assign_vars(array('NUM_DISABLED_USERS' => 0, 'NO_DISABLED_RESULTS' => true));

		/* Fetch all expired users to show on the modal box */
		$stmnt = sprintf("SELECT profile.id, user, email, phone, CONCAT (first, ' ', middle, ' ', last, ' ', maiden) as fullname FROM login INNER JOIN profile on login.id=profile.userid WHERE expired=1 ORDER BY profile.id");

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;
			while ($row = $result->fetch_assoc())
			{
				$index++;
				
				$template->assign_block_vars('user_expired_list',
					array('MEMBERID' => $row['id'],
						'USERNAME' => $row['user'],
						'FULLNAME' => $row['fullname'],
						'EMAIL' => $row['email'],
						'PHONE' => $row['phone']));	
			}
			$template->assign_vars(array('NUM_EXPIRED_USERS' => $index, 'NO_EXPIRED_RESULTS' => false));
			
			$result->close();
		}
		else
			$template->assign_vars(array('NUM_EXPIRED_USERS' => 0, 'NO_EXPIRED_RESULTS' => true));
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
