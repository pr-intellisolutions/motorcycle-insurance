<?php
require_once('../common.php');

$template->set_custom_template(DIR_BASE.'login/styles', 'login', DIR_BASE.'styles');

if ($user->auth() && $user->passchg)
{
	if (isset($_POST['oldpass']) && isset($_POST['newpass']) && isset($_POST['confirm_newpass']))
	{
		if (!empty($_POST['oldpass']) && !empty($_POST['newpass']) && !empty($_POST['confirm_newpass']))
		{
			if ($_POST['oldpass'] !== $_POST['newpass'] && $_POST['newpass'] === $_POST['confirm_newpass'])
			{
				if ($user->change_pass($_POST['oldpass'], $_POST['newpass']))
				{
					header('Location: '.SITE_URL.'login');
					die();
				}
			}
		}
	}
	if ($user->passchg == User::OLDPASS_EXPIRED)
		$template->assign_var('PASS_EXPIRED', true);
	else
		$template->assign_var('PASS_EXPIRED', false);

	set_header();
	set_footer();

	$template->assign_vars(array('PASSWORD_CHANGE' => true,
		'PASSCHG_FORM_ACTION' => $_SERVER['PHP_SELF'],
		'PASSCHG_FORM_METHOD' => 'POST',
		'PASSCHG_INTERVAL' => $site_config->pass_expiration,
		'PASSCHG_ERROR_MESSAGE' => $user->error));

	$template->assign_block_vars('site_styles', array('STYLE_NAME' => SITE_URL.'login/styles/login.css'));

	$template->set_filenames(array('body' => 'login_page.html'));

	$template->display('body');
}
else
{
	header('Location: '.SITE_URL.'login');
	die();
}
?>