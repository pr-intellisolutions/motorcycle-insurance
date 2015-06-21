<?php

require_once('../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth())
{
	$stmnt = sprintf("SELECT user, lastvisit, lastip, lastbrowser FROM login WHERE id = '%s'", $user->user_id);

	$result = $user->sql_conn->query($stmnt);

	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();	

		$template->assign_vars(array('SIDE_CONTENT' => 'home',
			"FORM_ACTION" => $_SERVER['PHP_SELF'],
			"FORM_METHOD" => "POST",
			"FORM_STATUS" => "INVALID",
			"USERNAME" => $row['user'],
			"LAST_VISIT" => $row['lastvisit'],
			"LAST_IP_ADDRESS" => $row['lastip'],
			"LAST_BROWSER" => $row['lastbrowser'],
			"CURRENT_BROWSER" => $_SERVER['HTTP_USER_AGENT'],
			"CURRENT_IP_ADDRESS" => $_SERVER['REMOTE_ADDR'],
			"CURRENT_DATE" => date('Y-m-d G:i:s')));
	}
	else
		trigger_error('/admin/index.php: '.$this->sql_conn->connect_error, E_USER_ERROR);

	$result->close();

	if (isset($_POST['action']) && $_POST['action'] === 'saveSiteConfig')
	{		
		$site_config->site_name = $_POST['siteName'];
		$site_config->site_desc = $_POST['siteDesc'];
		$site_config->site_host = $_POST['siteHost'];
		$site_config->site_module = $_POST['siteModule'];
	

		$stmt = sprintf("UPDATE config SET site_name = '%s', site_desc = '%s', site_host = '%s', site_module = '%s'",
			$site_config->site_name, $site_config->site_desc, $site_config->site_host, $site_config->site_module);
		
		if (!$user->sql_conn->query($stmt))
			trigger_error('/admin/index.php: '.$user->sql_conn->error, E_USER_ERROR);

		$template->assign_var("FORM_STATUS", "SUCCESS");		
	}
	else if (isset($_POST['action']) && $_POST['action'] === 'saveDBConfig')
	{
		$user->db_host = $_POST['dbHost'];
		$user->db_port = $_POST['dbPort'];
		$user->db_user = $_POST['dbUser'];
		$user->db_pass = $_POST['dbPass'];
		$user->db_name = $_POST['dbName'];
	
		$user->save_db_config();

		$template->assign_var("FORM_STATUS", "SUCCESS");		
	}
	else if (isset($_POST['action']) && $_POST['action'] === 'saveSecurityConfig')
	{
		$site_config->user_minlen = $_POST['userMinLen'];
		$site_config->user_maxlen = $_POST['userMaxLen'];
		$site_config->user_complexity = $_POST['userComplexity'];
		$site_config->pass_minlen = $_POST['passMinLen'];
		$site_config->pass_maxlen = $_POST['passMaxLen'];
		$site_config->pass_complexity = $_POST['passComplexity'];
		$site_config->pass_expiration = $_POST['passExpiration'];
		$site_config->max_login_attempts = $_POST['maxLoginAttempts'];

		$stmt = sprintf("UPDATE config SET user_minlen = %d, user_maxlen = %d, user_complexity = '%s', pass_minlen = %d, pass_maxlen = %d,
			pass_complexity = '%s', pass_expiration = %d, max_login_attempts = %d",
			$site_config->user_minlen, $site_config->user_maxlen, $site_config->user_complexity, $site_config->pass_minlen, $site_config->pass_maxlen,
			$site_config->pass_complexity, $site_config->pass_expiration, $site_config->max_login_attempts);
		
		if (!$user->sql_conn->query($stmt))
			trigger_error('/admin/index.php: '.$user->sql_conn->error, E_USER_ERROR);

		$template->assign_var("FORM_STATUS", "SUCCESS");
	}
	else if (isset($_POST['action']) && $_POST['action'] === 'cancel')
	{
		$template->assign_var("FORM_STATUS", "CANCEL");
	}
	else if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$template->assign_vars(array("SIDE_CONTENT" => 1,
			"SITE_NAME" => $site_config->site_name,
			"SITE_DESC" => $site_config->site_desc,
			"SITE_HOST" => $site_config->site_host,
			"SITE_MODULE" => $site_config->site_module));
	}

	else if (isset($_GET['option']) && $_GET['option'] == 2)
	{
		$template->assign_vars(array("SIDE_CONTENT" => 2,
			"DB_HOST" => $user->db_host,
			"DB_PORT" => $user->db_port,
			"DB_NAME" => $user->db_name,
			"DB_USER" => $user->db_user,
			"DB_PASS" => $user->db_pass));
	}
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
	}
	else if (isset($_GET['option']) && $_GET['option'] == 4)
		$template->assign_var("SIDE_CONTENT", 4);

	else if (isset($_GET['option']) && $_GET['option'] == 5)
		$template->assign_var("SIDE_CONTENT", 5);

	$template->set_filenames(array('body' => 'admin_cp.html'));

	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
