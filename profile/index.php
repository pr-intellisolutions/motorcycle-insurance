<?php
	
require_once('../common.php');
require_once('../includes/profile.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth())
{
	$template->assign_vars(array('SITE_URL' => SITE_URL,
		'FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST',
		'USER_AUTH_VALID' => true,
		'USER_ROLE' => $user->role,
		'NO_LOGIN_INFO' => false));
		
	$stmnt = sprintf("SELECT first, middle, last FROM profile INNER JOIN login ON profile.userid = login.id WHERE profile.userid=%d", $user->user_id);
	
	$result = $user->sql_conn->query($stmnt);
	
	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();

		$template->assign_var('USERNAME', sprintf("%s %s %s", $row['first'], $row['middle'], $row['last']));

		$result->close();
	}
	// Process PayPal confirmation
	// Bug #0x001 - Illegal payments can get through the system without actually paying for services
	$template->assign_var('BUY_PLAN', false);
	if (isset($_GET['trf']) && $_GET['trf'] === "PayPal")
	{
		// Process PayPal payment confirmation
		$stmnt = sprintf("INSERT INTO sales(userid, plan_id, transaction, method, amount, date) VALUES(%d, %d, '%s', '%s', %f, now())", 
			$user->user_id, $_GET['item_number'], $_GET['tx'], $_GET['trf'], $_GET['amt']);
		
		$user->sql_conn->query($stmnt);
		
		// Add plan to the database and link to the user
		$stmnt = sprintf("INSERT INTO services(userid, plan_id, reg_date, exp_date)
			VALUES (%d, %d, now(), date_add(now(), INTERVAL (select term from plans where id=%d) MONTH))",
			$user->user_id, $_GET['item_number'], $_GET['item_number']);
			
		$user->sql_conn->query($stmnt);

		// Show status message
		$template->assign_var('BUY_PLAN', true);
	}
	
	// Check if user service plan has any vehicles attached to it
	$stmnt = sprintf("SELECT * FROM services WHERE userid = %d", $user->user_id);
	$result = $user->sql_conn->query($stmnt);
	$template->assign_var('REGISTER_VEHICLE', true);
	if ($result->num_rows > 0)
	{
		$stmnt = sprintf("SELECT * FROM vehicles WHERE userid = %d", $user->user_id);
		$result = $user->sql_conn->query($stmnt);
		if ($result->num_rows > 0)
		{
			$template->assign_var('REGISTER_VEHICLE', false);
		}
		$result->close();
	}
	$template->assign_vars(array('SAVE_DATA_ERROR'=> false,
		'SAVE_DATA_ERROR_MGS' => ''));

	//# Show modify profile content
	if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$stmnt = sprintf("SELECT * FROM profile INNER JOIN login ON profile.userid = login.id WHERE login.id = %d", $user->user_id);

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
		$template->assign_var('SIDE_CONTENT', 1);
	}
	//# Show service plan content
	else if (isset($_GET['option']) && $_GET['option'] == 2)
	{
		$stmnt = sprintf("SELECT s.id, s.occurrence_counter, s.miles_counter, s.exp_date, p.title, p.num_occurrences, p.num_miles FROM login l, services s, plans p WHERE l.id=s.userid and p.id=s.plan_id and l.id=%d", $user->user_id);
		
		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;

			while ($row = $result->fetch_assoc())
			{
				$index++;
	
				$template->assign_block_vars('user_plans', array(
					'INDEX' => $index,
					'SERVICE_ID' => $row['id'],
					'PLAN_NAME' => $row['title'],
					'OCCUR_CURR' => $row['occurrence_counter'],
					'OCCUR_MAX' => $row['num_occurrences'],
					'MILE_CURR' => $row['miles_counter'],
					'MILE_MAX' => $row['num_miles'],
					'PLAN_EXP' => $row['exp_date']));
			}
		}
		$template->assign_var('SIDE_CONTENT', 2);
	}
	//# Show vehicles content
	else if (isset($_GET['option']) && $_GET['option'] == 3)
	{
		$stmnt = sprintf("SELECT * FROM login, services, vehicles, plans WHERE login.id=services.userid and services.id=vehicles.service_id and services.plan_id=plans.id and login.id=%d", $user->user_id);
		
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
		}
		$template->assign_var('SIDE_CONTENT', 3);
	}
	//# Show payment history content
	else if (isset($_GET['option']) && $_GET['option'] == 4)
	{
		$stmnt = sprintf("SELECT * FROM sales WHERE userid=%d", $user->user_id);
		
		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$template->assign_block_vars('payment_history', array('TX' => $row['transaction'],
					'AMT' => $row['amount'],
					'METHOD' => $row['method'],
					'DATE' => $row['date']));
			}
			$result->close();
		}

		$template->assign_var('SIDE_CONTENT', 4);
	}
	//# Process save profile
	else if (isset($_POST['action']) && $_POST['action'] === 'save_user')
	{
		$profile = new Profile;

		if (!$profile->save_profile($user->user_id, $_POST))
			$template->assign_vars(array('SAVE_DATA_ERROR'=> true,
				'SAVE_DATA_ERROR_MGS' => $profile->error));

		$stmnt = sprintf("SELECT * FROM profile INNER JOIN login ON profile.userid = login.id WHERE login.id = %d", $user->user_id);

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
		$template->assign_var('SIDE_CONTENT', 1);
	}
	//# Show profile content
	else
	{
		$stmnt = sprintf("SELECT login.email, profile.id FROM login INNER JOIN profile ON profile.userid=login.id WHERE profile.userid=%d", $user->user_id);
		
		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
	
			$template->assign_vars(array('U_MEMBER_ID' => $row['id'],
				'U_EMAIL' => $row['email']));
		}
		$template->assign_var('SIDE_CONTENT', 'home');
	}

	$template->set_filenames(array('body' => 'profile.html'));
	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}

?>
