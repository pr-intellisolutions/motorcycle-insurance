<?php

require_once('../../common.php');
require_once('./plan.php');

$plan = new Plan;

$plan_name = isset($_POST['name']) ? $_POST['name'] : "";

if ($plan->delete_plan($plan_name))
	echo "success";
else
	echo $plan->error;
?>
