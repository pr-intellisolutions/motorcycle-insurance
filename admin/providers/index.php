<?php

require_once('../../common.php');
require_once('../../includes/profile.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

if ($user->auth() && $user->role == 'admin')
{
	$template->assign_vars(array('SITE_URL' => SITE_URL, 
		'FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST',
		'USERNAME' => $user->user));
	
	//SIDE INTERFACES
	// Search provider by 
	if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 0));
	}
	// Modify provider
	else if (isset($_GET['option']) && $_GET['option'] == 2 )
	{
		$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 0));
	}
	// add provider 
	else if (isset($_GET['option']) && $_GET['option'] == 3 )
	{
		$template->assign_var('SIDE_CONTENT', '3');
	}
	// generate statistics
	else if (isset($_GET['option']) && $_GET['option'] == 4 )
	{
		$template->assign_var('SIDE_CONTENT', '4');
	}
	
	//SEARCH PROVIDERS PROCESS
	else if (isset($_POST['action']) && $_POST['action'] === 'search_provider')
	{
		switch($_POST['searchType'])
		{
			case 'area':
				$stmnt = sprintf("SELECT login.user, providers.companyName, providers.area, providers.companyPhone, providers.companyEmail FROM providers, login WHERE login.id = providers.userid AND providers.area='%s' ORDER BY providers.companyName", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 1));
					$index = 0;
					while ($row = $result->fetch_assoc())
					{
					$index++;
					$template->assign_block_vars('user_reg_list',
					array('INDEX' => $index, 'AREA' => $row['area'], 'COMPANYNAME' => $row['companyName'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail'], 'USER' => $row ['user']));
					}
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
			break;
			case 'companyName':
				$stmnt = sprintf("SELECT login.user, providers.companyName, providers.area, providers.companyPhone, providers.companyEmail FROM providers, login WHERE login.id = providers.userid AND providers.companyName = '%s' ORDER BY providers.area", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 1));
					$index = 0;
					while ($row = $result->fetch_assoc())
					{
					$index++;
					$template->assign_block_vars('user_reg_list',
					array('INDEX' => $index, 'COMPANYNAME' => $row['companyName'], 'AREA' => $row['area'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail'], 'USER' => $row ['user']));
					}
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
			break;
			default:
			break;
		}
	}
	
	// MODIFY PROVIDER PROCESS
	else if (isset($_POST['action']) && $_POST['action'] == 'modify_provider')
	{
		
		switch($_POST['searchType'])
		{
			case 'user':
				$stmnt = sprintf("SELECT * FROM providers, login WHERE providers.userid = login.id AND login.user= '%s'", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 1,
						'USERID' => $row['userid'],
						'COMPANYNAME' => $row['companyName'],
						'COMPANYPHONE' => $row['companyPhone'],
						'COMPANYEMAIL' => $row['companyEmail'],
						'AREA' => $row['area'],
						'ADDRESS1' => $row['address1'],
						'ADDRESS2' => $row['address2'],
						'CITY' => $row['city'],
						'ZIP' => $row['zip'],
						'COUNTRY' => $row['country']));
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
				break;
				
			case 'companyName':
				$stmnt = sprintf("SELECT * FROM providers WHERE companyName = '%s'", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();

					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 1,
						'USERID' => $row['userid'],
						'COMPANYNAME' => $row['companyName'],
						'COMPANYPHONE' => $row['companyPhone'],
						'COMPANYEMAIL' => $row['companyEmail'],
						'AREA' => $row['area'],
						'ADDRESS1' => $row['address1'],
						'ADDRESS2' => $row['address2'],
						'CITY' => $row['city'],
						'ZIP' => $row['zip'],
						'COUNTRY' => $row['country']));
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
				break;
			default:
				break;
		}
	}
	
	// PROVIDER CREATION PROCESS
	else if (isset($_POST['action']) && $_POST['action'] === 'add_provider')
	{
		$profile = new Profile;
		
		if ($profile->create_account($_POST))
			$template->assign_var('SIDE_CONTENT', 'create_account_successful');
		else
		{
			$template->assign_vars(array('SIDE_CONTENT' => 'create_account_failed',
				'ERROR_MESSAGE' => $profile->error));
		}
	}
	
	//PROVIDERS HOME 
	else 
	{
		$template->assign_vars(array('SIDE_CONTENT' => 'home'));

		if (($_POST['searchType'])=='companyName')
		{
			$stmnt = sprintf("SELECT companyName, area, companyPhone, companyEmail FROM providers ORDER BY companyName ASC, area ASC");
			$result = $user->sql_conn->query($stmnt);
			if ($result->num_rows > 0)
			{
				$index = 0;
				while ($row = $result->fetch_assoc())
				{
					$index++;
					$template->assign_block_vars('user_reg_list',
					array('INDEX' => $index, 'COMPANYNAME' => $row['companyName'], 'AREA' => $row['area'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail']));
				}
			}
			$result->close();
		} 	
		else 
		{
			$stmnt = sprintf("SELECT companyName, area, companyPhone, companyEmail FROM providers ORDER BY area ASC, companyName ASC");
			$result = $user->sql_conn->query($stmnt);
			if ($result->num_rows > 0)
			{
				$index = 0;
				while ($row = $result->fetch_assoc())
				{
					$index++;
					$template->assign_block_vars('user_reg_list',
					array('INDEX' => $index, 'COMPANYNAME' => $row['companyName'], 'AREA' => $row['area'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail']));
				}
			}
			$result->close();
		}
				
	}

	$template->set_filenames(array('body' => 'admin_providers.html'));

	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
