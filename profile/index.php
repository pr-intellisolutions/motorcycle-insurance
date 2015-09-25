<?php
	
require_once('../common.php');

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
	if (isset($_GET['trf']) && $_GET['trf'] === "PayPal")
	{
		// Process PayPal payment confirmation
		$stmnt = sprintf("INSERT INTO sales(userid, plan_id, transaction, method, amount, date) VALUES(%d, %d, '%s', '%s', %f, now())", 
			$user->user_id, $_GET['item_number'], $_GET['tx'], $_GET['trf'], $_GET['amt']);
		
		$user->sql_conn->query($stmnt);
		
		// Add plan to the database and link to the user
		// Add transaction to the finance section (possible sells table)
		// Show status message
	}
	
	// Check if user service plan has any vehicles attached to it
	{
		
	}
	
	//# Show modify profile content
	if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$template->assign_var('SIDE_CONTENT', 1);
	}
	//# Show service plan content
	else if (isset($_GET['option']) && $_GET['option'] == 2)
	{
		$template->assign_var('SIDE_CONTENT', 2);
	}
	//# Show vehicles content
	else if (isset($_GET['option']) && $_GET['option'] == 3)
	{
		$template->assign_var('SIDE_CONTENT', 3);
	}
	//# Show payment history content
	else if (isset($_GET['option']) && $_GET['option'] == 4)
	{
		$template->assign_var('SIDE_CONTENT', 4);
	}
	//# Show profile content
	else
	{
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
