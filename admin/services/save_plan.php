<?php

require_once('../../common.php');
require_once('./plan.php');

$plan = new Plan;

if ($plan->save_plan($_POST))
	echo "success";
else
	echo $plan->error;
?>