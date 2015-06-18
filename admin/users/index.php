<?php

require_once('../../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');
$template->assign_var('SITE_URL', SITE_URL);

$template->set_filenames(array('body' => 'admin_user.html'));

$template->display('body');

?>
