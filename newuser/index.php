<?php 

require_once('../common.php');
require_once('../login/login.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_vars(array('SITE_URL' => SITE_URL,
	'FORM_ACTION' => $_SERVER['PHP_SELF'],
	'FORM_METHOD' => 'POST'));

if ($user->auth())
{
	header('Location: '.SITE_URL);
	die();
}
else
{
	if (isset($_POST['action']) && $_POST['action'] === 'createAccount')
	{
		if ($user->create_account($_POST))
		{
			$login = new Login;
			
			$login->validate_user($_POST['username'], $_POST['password']);

			header('Location: '.SITE_URL.'plans');
			die();
		}
		else
		{
			$template->assign_vars(array('USER_AUTH_VALID' => false,
				'NO_LOGIN_INFO' => true,
				'REGISTER_ERROR_MESSAGE' => $user->error));
		}

	}
	else
	{
		$template->assign_vars(array('USER_AUTH_VALID' => false,
			'NO_LOGIN_INFO' => true,
			'REGISTER_ERROR_MESSAGE' => ''));
	}
	$template->set_filenames(array('body' => 'register.html'));
	$template->display('body');
}

?>
