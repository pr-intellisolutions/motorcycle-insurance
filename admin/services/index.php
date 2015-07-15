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

		-- POST method --
		Process plan creation: # process plan creation
		Process plan edit:	   # process plan edit
		Process plan removal:  # process plan removal
		
	Note 1: This only controls what parts of the front-end are visible to the user at a time using
		    the back-end template engine.

	Note 2: The reason for having GET and POST methods is because GET method controls the presentation sequence
			and the POST method controls what is been processed in the background.
		  
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
		$template->assign_var('SIDE_CONTENT', '2');
	}
	//# remove plan content
	else if (isset($_GET['option']) && $_GET['option'] == 3)
	{
		$template->assign_var('SIDE_CONTENT', '3');
	}
	//# process plan creation
	else if (isset($_POST['action']) && $_POST['action'] == 'create_plan')
	{
		$plan = new Plan;
		
		$plan->create_plan($_POST);
	}
	//# process plan edit
	else if (isset($_POST['action']) && $_POST['action'] == 'edit_plan')
	{
		
	}
	//# process plan removal
	else if (isset($_POST['action']) && $_POST['action'] == 'remove_plan')
	{
		
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
