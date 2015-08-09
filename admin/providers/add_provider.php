<?php

require_once('../../common.php');
require_once('./provider.php');

$provider = new Provider();

if ($provider->add_provider($_POST))
{
	echo "success";
	die();
}
else
{
	echo $provider->error;
	die();
}

?>
