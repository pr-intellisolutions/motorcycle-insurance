<?php

require_once('../../common.php');
require_once('./provider.php');

$provider = new Provider;

if ($provider->save_provider($_POST))
	echo "success";
else
	echo $provider->error;
?>
	
	// PROVIDER MODIFY PROCESS
	else if (isset($_POST['action']) && $_POST['action'] === 'modify_provider')
	{	
		$provider = new Provider;
	
		if ($provider->modify_provider($_POST))
			$template->assign_var('SIDE_CONTENT', 'modify_account_successful');
		else
		{
			$template->assign_vars(array('SIDE_CONTENT' => 'modify_account_failed', 'ERROR_MESSAGE' => $provider->error));
		}
		
	}