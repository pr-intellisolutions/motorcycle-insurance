<?php

require_once('../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->set_filenames(array('body' => 'admin_cp.html'));

$template->display('body');

?>
