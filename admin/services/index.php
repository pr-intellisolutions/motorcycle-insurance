<?php

require_once('../../common.php');
require_once('./plan.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

if ($user->auth() && $user->role == 'admin')
{
	/*
	Order of execution on page load:

		-- GET method --
		No conditions: # home content
		Plan creation: # add plan content
		Plan edit:	   # edit plan content
		Plan removal:  # remove plan content
		
	Note 1: This only controls what parts of the front-end are visible to the user at a time using
		    the back-end template engine.
		  
	*/
	$template->assign_vars(array('SITE_URL' => SITE_URL,
		'FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST',
		'USERNAME' => $user->user));

	//# add plan content
	if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$template->assign_var('SIDE_CONTENT', '1');
	}
	//# edit plan content
	else if (isset($_GET['option']) && $_GET['option'] == 2)
	{
		if (isset($_GET['modify']))
		{
			$template->assign_vars(array('SIDE_CONTENT' => '2', 'MODIFY' => true));
			
			$plan = New Plan;
			
			if ($plan_data = $plan->load_plan($_GET['plan']))
			{
				$template->assign_vars(array('PLAN_NAME' => $plan_data['name'],
					'PLAN_TITLE' => $plan_data['title'],
					'PLAN_DESC' => $plan_data['description'],
					'PLAN_OCCUR' => $plan_data['num_occurrences'],
					'PLAN_MILE' => $plan_data['num_miles'],
					'PLAN_VEHICLE' => $plan_data['num_vehicles'],
					'PLAN_PRICE' => $plan_data['plan_price'],
					'MILE_PRICE' => $plan_data['mile_price'],
					'EXTEND_PRICE' => $plan_data['extend_price'],
					'PLAN_TERM' => $plan_data['term']));
			}
			
			/* To do:
				1. Query the database
				2. Load the values into template variables
				3. Add the template variables into admin_services
			*/
		}
		else
		{
			$template->assign_vars(array('SIDE_CONTENT' => '2', 'MODIFY' => false));

			$stmnt = sprintf("SELECT name FROM plans");
			
			$result = $user->sql_conn->query($stmnt);
			
			if ($result->num_rows > 0)
				while ($row = $result->fetch_assoc())
					$template->assign_block_vars('plan_list', array('PLAN_NAME' => $row['name']));

			$result->close();
		}
	}
	//# remove plan content
	else if (isset($_GET['option']) && $_GET['option'] == 3)
	{
		$template->assign_var('SIDE_CONTENT', '3');
		
		$stmnt = sprintf("SELECT name FROM plans");
		
		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
			while ($row = $result->fetch_assoc())
				$template->assign_block_vars('plan_list', array('PLAN_NAME' => $row['name']));

		$result->close();
	}
	//# home content
	else
	{	
		// Load active plans
		$stmnt = sprintf("SELECT * FROM plans WHERE active=1");
		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;

			while ($row = $result->fetch_assoc())
			{
				$index++;

				$template->assign_block_vars('active_plan_list',
					array('PLAN_INDEX' => $index,
						  'PLAN_NAME' => $row['name'],
						  'PLAN_TITLE' => $row['title'],
						  'PLAN_DESC' => $row['description']));
			}
			$template->assign_var('ACTIVE_PLANS', $index);
		}
		else
			$template->assign_var('ACTIVE_PLANS', 0);

		
		$result->close();

		// Load inactive plans
		$stmnt = sprintf("SELECT * FROM plans WHERE active=0");
		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$index = 0;

			while ($row = $result->fetch_assoc())
			{
				$index++;

				$template->assign_block_vars('inactive_plan_list',
					array('PLAN_INDEX' => $index,
						  'PLAN_NAME' => $row['name'],
						  'PLAN_TITLE' => $row['title'],
						  'PLAN_DESC' => $row['description']));
			}
			$template->assign_var('INACTIVE_PLANS', $index);
		}
		else
			$template->assign_var('INACTIVE_PLANS', 0);

		$result->close();
	
		$template->assign_vars(array('SIDE_CONTENT' => 'home'));
	}

	$template->set_filenames(array('body' => 'admin_services.html'));
	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
