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
			
			//$_GET['plan'] holds the name of the plan to match with the database
			
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
