<?php

require_once('../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth())
{
	$stmnt = sprintf("SELECT * FROM login WHERE user = '%s'", $user->user);

	$result = $user->sql_conn->query($stmnt);

	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();	

		$template->assign_vars(array('CONTEXT_MENU' => 'home',
			"FORM_ACTION" => $_SERVER['PHP_SELF'],
			"FORM_METHOD" => "POST",
			"FORM_STATUS" => "INVALID",
			"USERNAME" => $row['user'],
			"LAST_VISIT" => $row['lastvisit'],
			"LAST_IP_ADDRESS" => $row['lastvisitip'],
			"LAST_BROWSER" => $row['lastvisitbrowser'],
			"CURRENT_BROWSER" => $_SERVER['HTTP_USER_AGENT'],
			"CURRENT_IP_ADDRESS" => $_SERVER['REMOTE_ADDR'],
			"CURRENT_DATE" => date('Y-m-d G:i:s')));
	}
	else
		trigger_error('AdminCP::PageLoad(): '.$this->sql_conn->connect_error, E_USER_ERROR);

	$result->close();

	if (isset($_POST['action']) && $_POST['action'] === 'saveSiteConfig')
	{		
		$site_config->site_name = $_POST['siteName'];
		$site_config->site_desc = $_POST['siteDesc'];
		$site_config->site_host = $_POST['siteHost'];
		$site_config->site_module = $_POST['siteModule'];
		
		// TODO: Save info into file
		//--------------------------------------------------
		$template->assign_var("FORM_STATUS", "SUCCESS");
	}
	else if (isset($_POST['action']) && $_POST['action'] === 'saveSiteDB')
	{
	}
	else if (isset($_POST['action']) && $_POST['action'] === 'saveSecurity')
	{
	}
	else if (isset($_POST['action']) && $_POST['action'] === 'cancel')
	{
		$template->assign_var("FORM_STATUS", "CANCEL");
	}
	else if (isset($_GET['option']) && $_GET['option'] === 'general')
	{
		$template->assign_vars(array("CONTEXT_MENU" => 'general',
			"SITE_NAME" => $site_config->site_name,
			"SITE_DESC" => $site_config->site_desc,
			"SITE_HOST" => $site_config->site_host,
			"SITE_MODULE" => $site_config->site_module));
	}

	else if (isset($_GET['option']) && $_GET['option'] === 'db')
	{
		$template->assign_vars(array("CONTEXT_MENU" => 'db',
			"DB_HOST" => $site_config->db_host,
			"DB_PORT" => $site_config->db_port,
			"DB_NAME" => $site_config->db_name,
			"DB_USER" => $site_config->db_user,
			"DB_PASS" => $site_config->db_pass));
	}
	else if (isset($_GET['option']) && $_GET['option'] === 'security')
		$template->assign_var("CONTEXT_MENU", "security");

	else if (isset($_GET['option']) && $_GET['option'] === 'backup')
		$template->assign_var("CONTEXT_MENU", "backup");

	else if (isset($_GET['option']) && $_GET['option'] === 'restore')
		$template->assign_var("CONTEXT_MENU", "restore");

	$template->set_filenames(array('body' => 'admin_cp.html'));

	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
