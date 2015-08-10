
<?php

require_once('../../common.php');
require_once('./provider.php');

if ($user->auth() && $user->role === 'admin')
{
	$provider = new Provider();

	if ($provider->modify_provider($_POST))
	{
		echo "success";
		die();
	}
	else
	{
		echo $provider->error;
		die();
	}
}
else
{
	http_response_code(400);
	die();
}

?>
