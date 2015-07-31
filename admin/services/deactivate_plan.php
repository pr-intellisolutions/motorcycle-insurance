<?php

require_once('../../common.php');
require_once('./plan.php');

$plan_name = isset($_POST['plan_name']) ? $_POST['plan_name'] : "";

if ($plan_name === "")
{
	echo "Nada seleccionado.";
	die();
}

$plan = new Plan;

if ($plan->activate_plan($plan_name, false))
{
	echo "success";
	die();
}
else
{
	echo $plan->error;
	die();
}

?>
