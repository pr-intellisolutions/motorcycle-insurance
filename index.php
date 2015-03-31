<?php

require_once('common.php');

if ($user->auth())
	$template->assign_var('USER_AUTH_VALID', true);
else
{
	$template->assign_vars(array('USER_AUTH_VALID' => false,
		'LOGIN_FORM_ACTION' => $_SERVER['PHP_SELF'],
		'LOGIN_FORM_METHOD' => 'POST'));
}

$template->assign_var('SITE_TITLE', $site_config->site_name);
$template->assign_block_vars('site_styles', array(
	'STYLE_NAME' => 'http://'.$site_config->site_host.$site_config->site_module.'styles/default.css'));

$template->set_filenames(array('body' => 'body.html'));

$template->display('body');

?>
