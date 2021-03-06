<?php

require_once('../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth() && (isset($_SESSION['buy_plan']) && $_SESSION['buy_plan'] == true))
{
	$stmnt = sprintf("SELECT first, middle, last FROM profile INNER JOIN login ON profile.userid = login.id WHERE profile.userid=%d", $user->user_id);
	
	$result = $user->sql_conn->query($stmnt);
	
	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();

		$template->assign_var('USERNAME', sprintf("%s %s %s", $row['first'], $row['middle'], $row['last']));

		$result->close();
	}

	$stmnt = sprintf("SELECT id, title, description, plan_price, extend_price, num_vehicles FROM plans WHERE id=%d", $_SESSION['selected_plan']);
	
	$result = $user->sql_conn->query($stmnt);
	
	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		
		$template->assign_vars(array('PLAN_ID' => $row['id'],
			'PLAN_TITLE' => $row['title'],
			'PLAN_DESC' => $row['description'],
			'PLAN_PRICE' => $row['plan_price'],
			'PLAN_EXTENDED' => $row['extend_price']));

		$num_vehicles = $row['num_vehicles'];
		$i = 1;
		while ($i <= $num_vehicles)
		{
			$template->assign_block_vars('num_vehicles', array('INDEX' => $i));
			$i++;
		}
		$result->close();
	}

	$template->assign_vars(array('USER_AUTH_VALID' => true,
		'USER_ROLE' => $user->role,
		'NO_LOGIN_INFO' => false));

	$template->set_filenames(array('body' => 'shop.html'));
	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}

?>