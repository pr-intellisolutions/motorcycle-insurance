<?php

require_once('../common.php');


$site_config->site_name = $_POST['siteName'];
$site_config->site_desc = $_POST['siteDesc'];
$site_config->site_host = $_POST['siteHost'];
$site_config->site_module = $_POST['siteModule'];


$stmt = sprintf("UPDATE config SET site_name = '%s', site_desc = '%s', site_host = '%s', site_module = '%s'",
	$site_config->site_name, $site_config->site_desc, $site_config->site_host, $site_config->site_module);

if ($user->sql_conn->query($stmt))
{
	echo 'success';
	die();
}
else
{
	echo $user->sql_conn->error;
	die();
}


?>