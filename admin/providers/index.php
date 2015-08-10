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
	// add provider 
	if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$stmnt = sprintf("SELECT user FROM login AS l LEFT OUTER JOIN providers AS p ON l.id=p.userid WHERE l.role='provider' AND p.userid IS NULL");
		
		$result = $user->sql_conn->query($stmnt);
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
				$template->assign_block_vars('provider', array('USERNAME' => $row['user']));
			
			$result->close();
		}
		$template->assign_var('SIDE_CONTENT', '1');
	}
	// delete provider 
	else if (isset($_GET['option']) && $_GET['option'] == 2)
	{
		$stmnt = sprintf("SELECT l.user, CONCAT (pr.first, ' ', pr.middle, ' ', pr.last, ' ', pr.maiden) as fullName FROM providers p, profile pr, login l WHERE p.userid=pr.userid AND l.id=pr.userid ORDER BY p.area ASC");
		
		$result = $user->sql_conn->query($stmnt);
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
				$template->assign_block_vars('provider', array('USERNAME' => $row['user'], 'FULLNAME' => $row['fullName']));
			
			$result->close();
		}
		$template->assign_var('SIDE_CONTENT', '2');
	}
	// modify provider
	else if (isset($_GET['option']) && $_GET['option'] == 3)
	{
		$stmnt = sprintf("SELECT l.user, CONCAT (pr.first, ' ', pr.middle, ' ', pr.last, ' ', pr.maiden) as fullName FROM providers p, profile pr, login l WHERE p.userid=pr.userid AND l.id=pr.userid ORDER BY p.area ASC");
		
		$result = $user->sql_conn->query($stmnt);
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
				$template->assign_block_vars('provider', array('USERNAME' => $row['user'], 'FULLNAME' => $row['fullName']));
			
			$result->close();
		}
		$template->assign_vars(array('SIDE_CONTENT' => '3', 'USERNAME_FOUND' => 0));
	}

	// DISPLAY/CHANGE PROVIDER 
	else if (isset($_POST['action']) && $_POST['action'] == 'show_modify_provider')
	{
		$stmnt = sprintf("SELECT l.user, p.profile_id, p.companyName, p.companyPhone, p.companyEmail, p.area, p.companyAddress1, p.companyAddress2, p.zip, p.city, p.country FROM providers p, login l WHERE p.userid=l.id AND l.user = '%s'", $_POST['user']);				

		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 1,
				'USERNAME' => $row['user'],
				'PROFILE_ID' => $row['profile_id'],
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
			$template->assign_vars(array('SIDE_CONTENT' => 3, 'USERNAME_FOUND' => 2));
		}					
		$result->close();
	}
	
	//PROVIDER FILTERING PROCESS
	else if (isset($_POST['action']) && $_POST['action'] === 'filter_by')
	{
		if ($_POST['searchInput'] === '')
		{
			$stmnt = sprintf("SELECT  p.area, p.companyPhone, p.city, p.companyEmail, CONCAT (pr.first, ' ', pr.middle, ' ', pr.last, ' ', pr.maiden) as fullName FROM providers p, profile pr WHERE p.userid=pr.userid ORDER BY p.area ASC;");

			$result = $user->sql_conn->query($stmnt);

			if ($result->num_rows > 0)
			{
				$index = 0;

				while ($row = $result->fetch_assoc())
				{
					$index++;

					$template->assign_block_vars('providers_list',
					array('INDEX' => $index,
						'FULLNAME' => $row['fullName'],
						'AREA' => $row['area'],
						'CITY' => $row ['city'],
						'COMPANYPHONE' => $row['companyPhone'],
						'COMPANYEMAIL' => $row['companyEmail']));
				}
				$result->close();
			}
		}
		else
		{
			switch($_POST['searchType'])
			{
				case 'provider':
					sscanf($_POST['searchInput'], "%s %s", $first, $last);

					$stmnt = sprintf("SELECT  p.area, p.companyPhone, p.city, p.companyEmail, CONCAT (pr.first, ' ', pr.middle, ' ', pr.last, ' ', pr.maiden) as fullName FROM providers p, profile pr WHERE p.userid=pr.userid AND pr.first='%s' AND pr.last='%s' ORDER BY area;", $first, $last);

					$result = $user->sql_conn->query($stmnt);

					if ($result->num_rows > 0)
					{
						$index = 0;

						while ($row = $result->fetch_assoc())
						{
							$index++;

							$template->assign_block_vars('providers_list',
							array('INDEX' => $index,
								'FULLNAME' => $row['fullName'],
								'AREA' => $row['area'],
								'CITY' => $row ['city'],
								'COMPANYPHONE' => $row['companyPhone'],
								'COMPANYEMAIL' => $row['companyEmail']));
						}
						$result->close();
					}
					break;
				case 'area': 	
					$stmnt = sprintf("SELECT  p.area, p.companyPhone, p.city, p.companyEmail, CONCAT (pr.first, ' ', pr.middle, ' ', pr.last, ' ', pr.maiden) as fullName FROM providers p, profile pr WHERE p.userid=pr.userid AND p.area = '%s' ORDER BY area;", $_POST['searchInput']);

					$result = $user->sql_conn->query($stmnt);

					if ($result->num_rows > 0)
					{
						$index = 0;

						while ($row = $result->fetch_assoc())
						{
							$index++;

							$template->assign_block_vars('providers_list',
							array('INDEX' => $index,
								'FULLNAME' => $row['fullName'],
								'AREA' => $row['area'],
								'CITY' => $row ['city'],
								'COMPANYPHONE' => $row['companyPhone'],
								'COMPANYEMAIL' => $row['companyEmail']));
						}
						$result->close();
					}
					break;
				case 'city':
					$stmnt = sprintf("SELECT  p.area, p.companyPhone, p.city, p.companyEmail, CONCAT (pr.first, ' ', pr.middle, ' ', pr.last, ' ', pr.maiden) as fullName FROM providers p, profile pr WHERE p.userid=pr.userid AND p.city = '%s' ORDER BY area;", $_POST['searchInput']);

					$result = $user->sql_conn->query($stmnt);

					if ($result->num_rows > 0)
					{
						$index = 0;

						while ($row = $result->fetch_assoc())
						{
							$index++;

							$template->assign_block_vars('providers_list',
							array('INDEX' => $index,
								'FULLNAME' => $row['fullName'],
								'AREA' => $row['area'],
								'CITY' => $row ['city'],
								'COMPANYPHONE' => $row['companyPhone'],
								'COMPANYEMAIL' => $row['companyEmail']));
						}
						$result->close();
					}
					break;
			}
		}
		$template->assign_var('SIDE_CONTENT', 'home');
	}
	
	//PROVIDERS HOME 
	else 
	{
		$stmnt = sprintf("SELECT  p.area, p.companyPhone, p.city, p.companyEmail, CONCAT (pr.first, ' ', pr.middle, ' ', pr.last, ' ', pr.maiden) as fullName FROM providers p, profile pr WHERE p.userid=pr.userid ORDER BY p.area ASC;");

		$result = $user->sql_conn->query($stmnt);

		if ($result->num_rows > 0)
		{
			$index = 0;

			while ($row = $result->fetch_assoc())
			{
				$index++;

				$template->assign_block_vars('providers_list',
				array('INDEX' => $index,
					'FULLNAME' => $row['fullName'],
					'AREA' => $row['area'],
					'CITY' => $row ['city'],
					'COMPANYPHONE' => $row['companyPhone'],
					'COMPANYEMAIL' => $row['companyEmail']));
			}
			$result->close();
		}
		$template->assign_var('SIDE_CONTENT', 'home');
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
