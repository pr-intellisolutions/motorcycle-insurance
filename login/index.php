<?php
require_once('../common.php');

$template->set_custom_template(DIR_BASE.'login/styles', 'login', DIR_BASE.'styles');

if ($user->auth())
{
	header('Location: '.SITE_URL);
	die();
}

require_once('./login.php');

$login = new Login;

$error_message = "";

if (isset($_POST['username']) && isset($_POST['password']))
{
	if (!empty($_POST['username']) && !empty($_POST['password']))
	{
		if ($login->validate_user($_POST['username'], $_POST['password']))
		{
			if ($login->passchg == User::OLDPASS_EXPIRED)
			{
				header('Location: '.SITE_URL.'login/password.reset.php');
				die();
			}
			else
			{
				header('Location: '.SITE_URL);
				die();
			}
		}
		else
		{
			$error_message = $login->error;
			$login->session_close();
		}
	}
}
?>
