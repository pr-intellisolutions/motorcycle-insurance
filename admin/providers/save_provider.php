<?php

require_once('../../common.php');
require_once('./provider.php');

$provider = new Provider;

if ($provider->save_provider($_POST))
	echo "success";
else
	echo $provider->error;
?>
