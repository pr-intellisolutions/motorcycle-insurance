<?php
require_once('../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');
$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth())
{
	header('Location: '.SITE_URL);
	die();
}
else
{
	$template->assign_vars(array('USER_AUTH_VALID' => false,
		'NO_LOGIN_INFO' => true));
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
				if (isset($_POST['remember_me']) && $_POST['remember_me'] === "true")
					$login->set_remember_me($_POST['username']);

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

$template->assign_vars(array('LOGIN_FORM_ACTION' => $_SERVER['PHP_SELF'],
	'LOGIN_FORM_METHOD' => 'POST',
	'LOGIN_ERROR_MESSAGE' => $error_message));

$template->set_filenames(array('body' => 'login.html'));

$template->display('body');
?>
