<?php

require_once('../../common.php');
require_once('./plan.php');

$name = isset($_POST['name']) ? $_POST['name'] : "";

$plan = new Plan;

if ($plan->delete_plan($name))
	echo "success";
else
	echo $plan->error;
?>
