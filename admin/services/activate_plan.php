<?php

require_once('../../common.php');
require_once('./plan.php');

$plan_name = isset($_POST['plan_name']) ? $_POST['plan_name'] : "";

$plan = new Plan;

if ($plan->activate_plan($plan_name, true))
	echo "success";
else
	echo $plan->error;
?>
