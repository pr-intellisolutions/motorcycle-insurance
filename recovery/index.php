<?php

require_once('../common.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

$template->assign_var('SITE_URL', SITE_URL);

if ($user->auth())
{
	header('Location: '.SITE_URL);
	die();
}
else
{
	$template->assign_vars(array('NO_LOGIN_INFO' => true,
		'USER_AUTH_VALID' => false,
		'USER_ROLE' => null,
		'FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST'));
	
	//# Process password change request
	if (isset($_POST['action']) && $_POST['action'] === "reset_password")
	{
		if (empty($_POST['email']))
		{
			$template->assign_vars(array("SIDE_CONTENT" => "home",
				"ERROR_STATUS" => "No se introdujo ningún correro electrónico"));
		}
		else
		{
			$email = $user->sanitize_input($_POST['email']);

			$stmnt = sprintf("SELECT id, email FROM login WHERE email = '%s'", $email);
			
			$result = $user->sql_conn->query($stmnt);
			
			if ($result->num_rows > 0)
			{
				$row = $result->fetch_assoc();
				
				$token = sha1(openssl_random_pseudo_bytes(256));

				$stmnt = sprintf("INSERT INTO reset(userid, token) VALUES(%d, '%s');", $row['id'], $token);
				if ($user->sql_conn->query($stmnt) == true)
				{
					$template->assign_vars(array("SIDE_CONTENT" => "home",
						"ERROR_STATUS" => "Se le ha enviado un correo electrónico con instrucciones para recuperar la contraseña."));
				}
				else
					$template->assign_vars(array("SIDE_CONTENT" => "home",
						"ERROR_STATUS" => "Ya se ha generado una solicitud para recuperar la contraseña."));
				
			}
			else
			{
				$template->assign_vars(array("SIDE_CONTENT" => "home",
					"ERROR_STATUS" => "El correo electrónico que se proveyó no está unido a ninguna cuenta de usuario."));

			}
			$result->close();

		}
	}
	//# Process new password change
	else if (isset($_POST['action']) && $_POST['action'] === "new_password")
	{
		if (empty($_POST['password']) || empty($_POST['pass_confirm']))
		{
			$template->assign_vars(array("SIDE_CONTENT" => "password_reset",
				"USER_ID" => $_POST['userid'], 
				"ERROR_STATUS" => "Llene los blancos requeridos para completar la transacción."));

		}
		else if ($_POST['password'] !== $_POST['pass_confirm'])
		{
			$template->assign_vars(array("SIDE_CONTENT" => "password_reset",
				"USER_ID" => $_POST['userid'], 
				"ERROR_STATUS" => "La confirmación de la contraseña no es correcta."));
		}
		else
		{
			if ($user->change_passwd($user->get_username($_POST['userid']), $_POST['password']))
			{

				$stmnt = sprintf("DELETE FROM reset WHERE token = '%s'", $_POST['token']);

				$user->sql_conn->query($stmnt);
				

				$template->assign_vars(array("SIDE_CONTENT" => "status",
					"PASSCHG_SUCCESS" => true, 
					"ERROR_STATUS" => "Su contraseña ha sido cambiada exitósamente"));
			}
			else
			{
				$template->assign_vars(array("SIDE_CONTENT" => "password_reset",
					"PASSCHG_SUCCESS" => false, 
					"ERROR_STATUS" => $user->error));
			}
		}
	}
	//# Reset password content
	else if (isset($_GET['token']))
	{
	
		$stmnt = sprintf("SELECT userid, token FROM reset WHERE token = '%s'", $_GET['token']);
		
		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();

			$template->assign_vars(array("SIDE_CONTENT" => "password_reset",
				"USER_ID" => $row['userid'], 
				"TOKEN" => $_GET['token'], 
				"ERROR_STATUS" => ""));
		}
		$result->close();
	}
	//# Request password content
	else
	{
		$template->assign_vars(array("SIDE_CONTENT" => "home",
			"ERROR_STATUS" => ""));
	}

	$template->set_filenames(array('body' => 'recovery.html'));
	$template->display('body');
}

?>
