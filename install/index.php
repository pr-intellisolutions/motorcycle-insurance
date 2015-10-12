<?php
require_once('../includes/template.php');
$template = new Template;

$template->set_custom_template('../styles', 'default');

if (isset($_GET['action']) && $_GET['action'] === 'install' && $_GET['step'] === 'database')
{
	$template->assign_var('BODY_CONTENT', 'database');

}
else if (isset($_GET['action']) && $_GET['action'] === 'install' && $_GET['step'] === 'domain')
{
	$template->assign_var('BODY_CONTENT', 'domain');
}
else if (isset($_GET['action']) && $_GET['action'] === 'install' && $_GET['step'] === 'administrator')
{
	$template->assign_var('BODY_CONTENT', 'administrator');
}
else if (isset($_GET['action']) && $_GET['action'] === 'install' && $_GET['step'] === 'finalize')
{
	$template->assign_var('BODY_CONTENT', 'finalize');
}
else
{
	$template->assign_var('BODY_CONTENT', 'install');
}

$template->set_filenames(array('body' => 'install.html'));
$template->display('body');

?>
