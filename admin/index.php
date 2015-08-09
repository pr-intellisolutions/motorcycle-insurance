<?php

require_once('../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

if ($user->auth() && $user->role === 'admin')
{
	/*
	Order of execution on page load:

		-- GET method --
		No conditions:			# home content
		Modify configuration:	# modify configuration content
		Modify database:		# modify database content
		Modify security:		# modify security policy content
		Database backup:		# Database backup content
		Database restore:		# Database restore content

	Note 1: This only controls what parts of the front-end are visible to the user at a time using
		    the back-end template engine.

	Note 2: All POST requests are now processed through AJAX.

	*/

	$template->assign_vars(array('SITE_URL' => SITE_URL,
		'FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST',
		'USERNAME' => $user->user));
	
	//# modify configuration content
	if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$template->assign_vars(array("SIDE_CONTENT" => 1,
			"SITE_NAME" => $site_config->site_name,
			"SITE_DESC" => $site_config->site_desc,
			"SITE_HOST" => $site_config->site_host,
			"SITE_MODULE" => $site_config->site_module));
	}
	//# modify database content
	else if (isset($_GET['option']) && $_GET['option'] == 2)
	{
		$template->assign_vars(array("SIDE_CONTENT" => 2,
			"DB_HOST" => $user->db_host,
			"DB_PORT" => $user->db_port,
			"DB_NAME" => $user->db_name,
			"DB_USER" => $user->db_user,
			"DB_PASS" => $user->db_pass));
	}
	//# modify security policy content
	else if (isset($_GET['option']) && $_GET['option'] == 3)
	{
		$template->assign_vars(array("SIDE_CONTENT" => 3,
			"USER_MINLEN" => $site_config->user_minlen,
			"USER_MAXLEN" => $site_config->user_maxlen,
			"PASS_MINLEN" => $site_config->pass_minlen,
			"PASS_MAXLEN" => $site_config->pass_maxlen,
			"PASS_EXPIRATION" => $site_config->pass_expiration,
			"MAX_LOGIN_ATTEMPTS" => $site_config->max_login_attempts));
		
		if ($site_config->user_complexity === "alphanumeric")
			$template->assign_var("ALPHANUMERIC_SELECTED", "selected");
		else
			$template->assign_var("ALPHA_SPACERS_SELECTED", "selected");

		if ($site_config->pass_complexity === "simple")
			$template->assign_var("SIMPLE_SELECTED", "selected");
		else if ($site_config->pass_complexity === "normal")
			$template->assign_var("NORMAL_SELECTED", "selected");
		else
			$template->assign_var("STRONG_SELECTED", "selected");
		
		if ($site_config->activation_req == true)
			$template->assign_var("ACTIVATION_REQUIRED", "selected");
		else
			$template->assign_var("ACTIVATION_NOT_REQUIRED", "selected");


	}
	//# Database backup content
	else if (isset($_GET['option']) && $_GET['option'] == 4)
		$template->assign_var("SIDE_CONTENT", 4);

	//# Database restore content
	else if (isset($_GET['option']) && $_GET['option'] == 5)
		$template->assign_var("SIDE_CONTENT", 5);

	//# home content
	else
	{
		$stmnt = sprintf("SELECT lastvisit, lastip, lastbrowser FROM login WHERE id = '%s'", $user->user_id);

		$result = $user->sql_conn->query($stmnt);

		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();

			$template->assign_vars(array('SIDE_CONTENT' => 'home',
				'LAST_VISIT' => $row['lastvisit'],
				'LAST_IP_ADDRESS' => $row['lastip'],
				'LAST_BROWSER' => $row['lastbrowser'],
				'CURRENT_BROWSER' => $_SERVER['HTTP_USER_AGENT'],
				'CURRENT_IP_ADDRESS' => $_SERVER['REMOTE_ADDR'],
				'CURRENT_DATE' => date('Y-m-d G:i:s')));

			$result->close();
		}
	}

	$template->set_filenames(array('body' => 'admin_cp.html'));
	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
