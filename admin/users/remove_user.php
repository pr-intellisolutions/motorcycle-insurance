<?php

require_once('../../common.php');

if (isset($_POST['username']) && $_POST['username'] !== "")
{
	$username = $user->sanitize_input($_POST['username']);			

	if ($user->delete_account($username))
	{
		echo "success";
		die();
	}
	else
	{
		echo $user->error;
		die();
	}
}
else if (isset($_POST['id']) && $_POST['id'] !== "")
{
	$member_id = $user->sanitize_input($_POST['id']);

	$stmt = sprintf("SELECT login.user FROM login INNER JOIN profile ON login.id = profile.userid WHERE profile.id = %d", $member_id);
	
	$result = $user->sql_conn->query($stmt);

	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		
		$username = $row['user'];

		if ($user->delete_account($username))
		{
			$result->close();

			echo "success";
			die();
		}
		else
		{
			echo $user->error;
			die();
		}
	}
	else
	{
		$result->close();

		$user->set_error($user::UNREGISTERED_USER);
		
		echo $user->error;
		die();
	}
}
else
{
	$user->set_error($user::BAD_INPUT);

	echo $user->error;
	die();
}
?>
