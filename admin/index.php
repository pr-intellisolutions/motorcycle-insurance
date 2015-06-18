<?php

require_once('../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth())
{
	$template->assign_vars(array('CONTEXT_MENU' => 'home',
		"FORM_ACTION" => $_SERVER['PHP_SELF'],
		"FORM_METHOD" => "POST",
		"FORM_STATUS" => "INVALID"));

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
