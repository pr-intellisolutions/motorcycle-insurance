<?php

require_once('../common.php');

$site_config->user_minlen = $_POST['userMinLen'];
$site_config->user_maxlen = $_POST['userMaxLen'];
$site_config->user_complexity = $_POST['userComplexity'];
$site_config->pass_minlen = $_POST['passMinLen'];
$site_config->pass_maxlen = $_POST['passMaxLen'];
$site_config->pass_complexity = $_POST['passComplexity'];
$site_config->pass_expiration = $_POST['passExpiration'];
$site_config->max_login_attempts = $_POST['maxLoginAttempts'];
$site_config->activation_req = $_POST['activationReq'];

$stmt = sprintf("UPDATE config SET user_minlen = %d, user_maxlen = %d, user_complexity = '%s', pass_minlen = %d, pass_maxlen = %d,
	pass_complexity = '%s', pass_expiration = %d, max_login_attempts = %d, activation_req = %d",
	$site_config->user_minlen, $site_config->user_maxlen, $site_config->user_complexity, $site_config->pass_minlen, $site_config->pass_maxlen,
	$site_config->pass_complexity, $site_config->pass_expiration, $site_config->max_login_attempts, $site_config->activation_req);

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