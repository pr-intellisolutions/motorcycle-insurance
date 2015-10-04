<?php

require_once('../../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth() && $user->role == 'admin')
{
	$template->assign_vars(array('FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST',
		'USERNAME' => $user->user));

	if (isset($_GET['option']) && $_GET['option'] == 1)
	{
		$template->assign_var('SIDE_CONTENT', 1);
		
		// Generate providers list
		$stmnt = sprintf("SELECT  p.id, p.area, p.companyPhone, p.city, p.companyEmail, CONCAT (pr.first, ' ', pr.middle, ' ', pr.last, ' ', pr.maiden) as fullName FROM providers p, profile pr WHERE p.userid=pr.userid ORDER BY p.area ASC;");

		$result = $user->sql_conn->query($stmnt);

		if ($result->num_rows > 0)
		{
			$index = 0;

			while ($row = $result->fetch_assoc())
			{
				$index++;

				$template->assign_block_vars('providers_list',
				array('PROVIDER_ID' => $row['id'],
					'PROVIDER_NAME' => $row['fullName']));
			}
			$result->close();
		}
	}
	else
	{
		$template->assign_var('SIDE_CONTENT', 'home');
	}		
	$template->set_filenames(array('body' => 'admin_assistance.html'));
	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
