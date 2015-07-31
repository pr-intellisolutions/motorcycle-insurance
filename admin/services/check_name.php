<?php

require_once('../../common.php');
require_once('./plan.php');

$name = isset($_POST['name']) ? $_POST['name'] : "";

$plan = New Plan;

if ($plan->name_available($name))
{
	echo "available";
	die();
}
else
{
	echo "not available";
	die();
}

?>
