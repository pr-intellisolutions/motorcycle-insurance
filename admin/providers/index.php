<?php

require_once('../../common.php');
require_once('./provider.php');

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
	// delete provider 
	else if (isset($_GET['option']) && $_GET['option'] == 4 )
	{
		$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 0));
	}
	// generate statistics
	else if (isset($_GET['option']) && $_GET['option'] == 5 )
	{
		$template->assign_var('SIDE_CONTENT', '5');
	}
	
	//SEARCH PROVIDERS PROCESS
	else if (isset($_POST['action']) && $_POST['action'] === 'search_provider')
	{
		switch($_POST['searchType'])
		{

			case 'area':
				$stmnt = sprintf("SELECT p.companyName, p.area, p.companyPhone, p.companyEmail, p.id, CONCAT (pr.name, ' ', pr.last) as nameLast, pr.userid FROM providers p, profile pr WHERE p.userid = pr.userid AND p.area='%s' ORDER BY p.companyName", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 1));
					$index = 0;
					while ($row = $result->fetch_assoc())
					{
					$index++;
					$template->assign_block_vars('user_reg_list',
					array('INDEX' => $index, 'AREA' => $row['area'], 'COMPANYNAME' => $row['companyName'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail'],  'ID' => $row ['id'], 'NAMELAST' => $row ['nameLast'], 'USERID' => $row ['userid']));
					}
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
			break;
			case 'companyName':
				$stmnt = sprintf("SELECT p.companyName, p.area, p.companyPhone, p.companyEmail, p.id, CONCAT (pr.name, ' ', pr.last) as nameLast, pr.userid FROM providers p, profile pr WHERE p.userid = pr.userid AND p.companyName REGEXP '%s' ORDER BY p.area", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 1));
					$index = 0;
					while ($row = $result->fetch_assoc())
					{
					$index++;
					$template->assign_block_vars('user_reg_list',
					array('INDEX' => $index, 'AREA' => $row['area'], 'COMPANYNAME' => $row['companyName'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail'],  'ID' => $row ['id'], 'NAMELAST' => $row ['nameLast'], 'USERID' => $row ['userid']));					
					}
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
			break;
			case 'nameLast':
				$stmnt = sprintf("SELECT p.companyName, p.area, p.companyPhone, p.companyEmail, p.id, CONCAT (pr.name, ' ', pr.last) as nameLast, pr.userid FROM providers p, profile pr WHERE p.userid = pr.userid AND CONCAT(pr.name, ' ', pr.last) REGEXP '%s'", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 1));
					$index = 0;
					while ($row = $result->fetch_assoc())
					{
					$index++;
					$template->assign_block_vars('user_reg_list',
					array('INDEX' => $index, 'AREA' => $row['area'], 'COMPANYNAME' => $row['companyName'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail'],  'ID' => $row ['id'], 'NAMELAST' => $row ['nameLast'], 'USERID' => $row ['userid']));					
					}
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
			break;
			case 'userid':
				$stmnt = sprintf("SELECT p.companyName, p.area, p.companyPhone, p.companyEmail, p.id, CONCAT (pr.name, ' ', pr.last) as nameLast, pr.userid FROM providers p, profile pr WHERE p.userid = pr.userid AND p.userid=%d ORDER BY p.companyName", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$template->assign_vars(array('SIDE_CONTENT' => 1, 'USERNAME_FOUND' => 1));
					$index = 0;
					while ($row = $result->fetch_assoc())
					{
					$index++;
					$template->assign_block_vars('user_reg_list',
					array('INDEX' => $index, 'AREA' => $row['area'], 'COMPANYNAME' => $row['companyName'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail'],  'ID' => $row ['id'], 'NAMELAST' => $row ['nameLast'], 'USERID' => $row ['userid']));					}
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
	
	// DISPLAY/CHANGE PROVIDER 
	else if (isset($_POST['action']) && $_POST['action'] == 'show_modify_provider')
	{
			
		switch($_POST['searchType'])
		{

			case 'userid':
				$stmnt = sprintf("SELECT * FROM providers WHERE userid = %d", $_POST['inputSearch']);				
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 1,
						'ID' => $row['id'],
						'USERID' => $row['userid'],
						'COMPANYNAME' => $row['companyName'],
						'COMPANYPHONE' => $row['companyPhone'],
						'COMPANYEMAIL' => $row['companyEmail'],
						'AREA' => $row['area'],
						'COMPANYADDRESS1' => $row['companyAddress1'],
						'COMPANYADDRESS2' => $row['companyAddress2'],
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
			case 'username':
				$stmnt = sprintf("SELECT p.id, p.userid, p.companyName, p.companyPhone, p.companyEmail, p.area, p.companyAddress1, p.companyAddress2, p.zip, p.city, p.country FROM providers p, login l WHERE p.userid=l.id AND l.user = '%s'", $_POST['inputSearch']);				
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$template->assign_vars(array('SIDE_CONTENT' => 2, 'USERNAME_FOUND' => 1,
						'ID' => $row['id'],
						'USERID' => $row['userid'],
						'COMPANYNAME' => $row['companyName'],
						'COMPANYPHONE' => $row['companyPhone'],
						'COMPANYEMAIL' => $row['companyEmail'],
						'AREA' => $row['area'],
						'COMPANYADDRESS1' => $row['companyAddress1'],
						'COMPANYADDRESS2' => $row['companyAddress2'],
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
	
	// PROVIDER CREATION PROCESS
	else if (isset($_POST['action']) && $_POST['action'] === 'add_provider')
	{	
		$provider = new Provider;
		
		switch($_POST['searchType'])
		{
			case 'username':
				if ($provider->add_provider_username($_POST))
					$template->assign_var('SIDE_CONTENT', 'create_account_successful');
				else
					$template->assign_vars(array('SIDE_CONTENT' => 'create_account_failed','ERROR_MESSAGE' => $provider->error));
			break;
			case 'userid':
				if ($provider->add_provider_userid($_POST))
					$template->assign_var('SIDE_CONTENT', 'create_account_successful');
				else
					$template->assign_vars(array('SIDE_CONTENT' => 'create_account_failed','ERROR_MESSAGE' => $provider->error));
			break;		
			default:
			break;
		}
		
	}
	
	
	// DISPLAY PROVIDER BEFORE DELETE
	else if (isset($_POST['action']) && $_POST['action'] === 'show_delete_provider')
	{		
		switch($_POST['searchType'])
		{
			case 'userid':
				$stmnt = sprintf("SELECT p.id, p.companyName, p.area, p.companyPhone, p.companyEmail, CONCAT(pr.name, ' ', pr.last) as nameLast FROM providers p, profile pr WHERE p.userid=pr.userid AND p.userid ='%d'", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 1, 'ID' => $row['id']));
					
					$template->assign_block_vars('user_reg_list',
					array('ID' => $row['id'], 'COMPANYNAME' => $row['companyName'], 'NAMELAST' => $row['nameLast'], 'AREA' => $row['area'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail']));
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
			break;
			case 'username':
				$stmnt = sprintf("SELECT p.id, p.companyName, p.area, p.companyPhone, p.companyEmail, CONCAT(pr.name, ' ', pr.last) as nameLast FROM providers p, profile pr, login l WHERE p.userid=pr.userid AND p.userid=l.id AND l.user = '%s'", $_POST['inputSearch']);
				$result = $user->sql_conn->query($stmnt);
				
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 1, 'ID' => $row['id']));
					
					$template->assign_block_vars('user_reg_list',
					array('ID' => $row['id'], 'COMPANYNAME' => $row['companyName'], 'NAMELAST' => $row['nameLast'], 'AREA' => $row['area'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail']));
				}
				else
				{
					$template->assign_vars(array('SIDE_CONTENT' => 4, 'USERNAME_FOUND' => 2));
				}					
				$result->close();
			break;
			default:
			break;
		}		
	}
	
	// PROVIDER DELETE PROCESS 
	else if (isset($_POST['action']) && $_POST['action'] === 'delete_provider')
	{	
		$provider = new Provider;
		
		if ($provider->delete_provider($_POST))
			$template->assign_var('SIDE_CONTENT', 'delete_account_successful');
		else
		{
			$template->assign_vars(array('SIDE_CONTENT' => 'delete_account_failed',
				'ERROR_MESSAGE' => $provider->error));
		}
		

	}
	
	
	//PROVIDER ORDERING PROCESS
	else if (isset($_POST['action']) && $_POST['action'] === 'order_by')
	{
			switch($_POST['searchType'])
		{
			case 'nameLast':
		$stmnt = sprintf("SELECT  p.area, p.companyPhone, p.city, p.companyEmail, CONCAT (pr.name, ' ', pr.Last) as nameLast FROM providers p, profile pr WHERE p.userid=pr.userid ORDER BY area ASC, nameLast ASC;");
				$result = $user->sql_conn->query($stmnt);
				if ($result->num_rows > 0)
				{
					$template->assign_vars(array('SIDE_CONTENT' => 'home', 'USERNAME_FOUND' => 1));
					$index = 0;
					while ($row = $result->fetch_assoc())
					{
						$index++;
						$template->assign_block_vars('user_reg_list',
						array('INDEX' => $index, 'NAMELAST' => $row['nameLast'], 'AREA' => $row['area'], 'CITY' =>  $row ['city'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail']));
					}
				}
				else 
				{
				$template->assign_vars(array('SIDE_CONTENT' => 'home', 'USERNAME_FOUND' => 2));
				}
				$result->close();
				break;	
			case 'area': 	
						$stmnt = sprintf("SELECT  p.area, p.companyPhone, p.city, p.companyEmail, CONCAT (pr.name, ' ', pr.Last) as nameLast FROM providers p, profile pr WHERE p.userid=pr.userid ORDER BY area;");
				$result = $user->sql_conn->query($stmnt);
				if ($result->num_rows > 0)
				{
					$template->assign_vars(array('SIDE_CONTENT' => 'home', 'USERNAME_FOUND' => 1));
					$index = 0;
					while ($row = $result->fetch_assoc())
					{
						$index++;
						$template->assign_block_vars('user_reg_list',
						array('INDEX' => $index, 'NAMELAST' => $row['nameLast'], 'AREA' => $row['area'], 'CITY' =>  $row ['city'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail']));
					}
				}
				else 
				{
				$template->assign_vars(array('SIDE_CONTENT' => 'home', 'USERNAME_FOUND' => 2));
				}
				$result->close();
				break; 
			default:
				break;
		}
	}
	
	//PROVIDERS HOME 
	else 
	{
		$template->assign_vars(array('SIDE_CONTENT' => 'home', 'USERNAME_FOUND' => 0));
		$stmnt = sprintf("SELECT  p.area, p.companyPhone, p.city, p.companyEmail, CONCAT (pr.first, ' ', pr.Last) as nameLast FROM providers p, profile pr WHERE p.userid=pr.userid ORDER BY p.area ASC;");
		$result = $user->sql_conn->query($stmnt);
			if ($result->num_rows > 0)
				{
					$index = 0;
					while ($row = $result->fetch_assoc())
					{
						$index++;
						$template->assign_block_vars('user_reg_list',
						array('INDEX' => $index, 'NAMELAST' => $row['nameLast'], 'AREA' => $row['area'], 'CITY' => $row ['city'], 'COMPANYPHONE' => $row['companyPhone'], 'COMPANYEMAIL' => $row['companyEmail']));
					}
				}
				else 
				{
				$template->assign_vars(array('SIDE_CONTENT' => 'home', 'USERNAME_FOUND' => 2));
				}
				$result->close();
				
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
