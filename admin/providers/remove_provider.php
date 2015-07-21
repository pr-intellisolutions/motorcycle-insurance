<?php

require_once('../../common.php');
require_once('./provider.php');

$id = isset($_POST['id']) ? $_POST['id'] : "";

$provider = new Provider;

if ($provider->delete_provider($id))
	echo "success";
else
	echo $provider->error;
?>
