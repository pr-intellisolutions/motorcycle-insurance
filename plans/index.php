<?php

require_once('../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

	$template->assign_vars(array('SITE_URL' => SITE_URL,
		'FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST'));

if ($user->auth())
{
	if (isset($_POST['plan_id']))
	{
		//add plan id to current session
		//redirect to shopping cart
		$_SESSION['buy_plan'] = true;
		$_SESSION['selected_plan'] = $_POST['plan_id'];
	
		header('Location: '.SITE_URL.'shop');
		die();
	}
	$stmnt = sprintf("SELECT first, middle, last FROM profile INNER JOIN login ON profile.userid = login.id WHERE profile.userid=%d", $user->user_id);
	
	$result = $user->sql_conn->query($stmnt);
	
	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();

		$template->assign_var('USERNAME', sprintf("%s %s %s", $row['first'], $row['middle'], $row['last']));

		$result->close();
	}
	$template->assign_vars(array('USER_AUTH_VALID' => true,
		'USER_ID'	=> $user->user_id,
		'USER_ROLE' => $user->role));
}
else
{
	if (isset($_POST['plan_id']))
	{
		//add plan id to current session
		//redirect user registration
		$_SESSION['buy_plan'] = true;
		$_SESSION['selected_plan'] = $_POST['plan_id'];
	
		header('Location: '.SITE_URL.'newuser');
		die();
	}
	$template->assign_vars(array('USER_AUTH_VALID' => false,
		'USER_ID'	=> $user->user_id,
		'USER_ROLE' => $user->role));
}

$stmnt = sprintf("SELECT * FROM plans WHERE active=1");

$result = $user->sql_conn->query($stmnt);

if ($result->num_rows > 0)
{
	while ($row = $result->fetch_assoc())
	{
		$template->assign_block_vars('plan',
			array('ID'	  => $row['id'],
				  'TITLE' => $row['title'],
				  'DESC'  => $row['description'],
				  'PRICE' => $row['plan_price']));
	}
	$result->close();
}
$template->assign_var('NO_LOGIN_INFO', false);

$template->set_filenames(array('body' => 'plans.html'));
$template->display('body');

?>
