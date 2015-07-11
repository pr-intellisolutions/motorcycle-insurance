<?php

class User extends Session
{
	// Error constants
	const SESSION_INVALID	= 0;
	const USER_INVALID		= 1;
	const PASS_INVALID		= 2;
	const NEWPASS_REQUEST	= 3;
	const OLDPASS_EXPIRED	= 4;

	public $error;

	function __construct()
	{
		// Only a resumed session contains valid data
		parent::__construct();
	}
	public function auth()
	{
		// Checking for a the session id guarantees that the user was previously validated through proper login
		if (!$this->session_id)
		{
			$this->set_error(self::SESSION_INVALID);
			return false;
		}

		$stmt = sprintf("SELECT * FROM login WHERE id = %d", $this->user_id);

		$result = $this->sql_conn->query($stmt);

		if ($result->num_rows == 0)
		{
			$this->set_error(self::USER_INVALID);
			return false;
		}

		$row = $result->fetch_assoc();
		
		// Check if password has been compromised
		$options = password_get_info ($this->pass);

		if (password_needs_rehash($this->pass, $options['algo'], $options['options']))
		{
			$this->set_error(self::PASS_INVALID);
			return false;
		}

		$result->close();

		return true;
	}
	public function change_pass($oldpass, $newpass)
	{
		if (!$this->session_id)
		{
			$this->set_error(self::SESSION_INVALID);
			return false;
		}

		$oldpass = $this->sanitize_input($oldpass);
		$newpass = $this->sanitize_input($newpass);

		$stmt = sprintf("SELECT * FROM login WHERE id = %d", $this->user_id);

		$result = $this->sql_conn->query($stmt);

		if ($result->num_rows == 0)
		{
			$this->set_error(self::USER_INVALID);
			return false;
		}

		$row = $result->fetch_assoc();

		if (!password_verify($oldpass, $row['pass']))
		{
			$this->set_error(self::PASS_INVALID);
			return false;
		}

		// Create date object with current date and time
		$passdate = date_create(date('Y-m-d G:i:s'));

		// Add the number of days in which the password should expire
		date_add($passdate, date_interval_create_from_date_string($this->pass_expiration.' days'));

		// Convert the date in MySQL format
		$passdate = date_format($passdate, 'Y-m-d G:i:s');

		// Encrypt new password
		$options = array('cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));

		$newpass = password_hash($newpass, PASSWORD_BCRYPT, $options);

		// Store the new password expiration date and reset password
		$stmt = sprintf("UPDATE login SET pass = '%s', passchg = 0, passdate = '%s' WHERE id = %d",
						$newpass, $passdate, $this->user_id);

		if (!$this->sql_conn->query($stmt))
			trigger_error('User::change_pass(): '.$this->sql_conn->error, E_USER_ERROR);

		// Update the user session with new password and reset password change flag to false
		$this->session_update('pass', $newpass);
		$this->session_update('passchg', false);

		$result->close();

		return true;
	}
	public function user_valid($username)
	{
		$isvalid;

		$stmt = sprintf("SELECT user FROM login WHERE user = '%s'", $username);

		$result = $this->sql_conn->query($stmt);

		if ($result->num_rows > 0)
			$isvalid = true;
		else
			$isvalid = false;
		
		$result->close();

		return $isvalid;
	}
	public function user_locked($username)
	{
		$islocked;

		$stmt = sprintf("SELECT disabled FROM login WHERE user = '%s'", $username);

		$result = $this->sql_conn->query($stmt);

		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			if ($row['disabled'] == 1)
				$islocked = true;
			else
				$islocked = false;
		}
		$result->close();

		return $islocked;
	}
	public function user_unlock($username)
	{
		$stmt = sprintf("UPDATE login SET disabled = 0 WHERE user = '%s'", $username);

		if (!$this->sql_conn->query($stmt))
			trigger_error('User::change_pass(): '.$this->sql_conn->error, E_USER_ERROR);
	}
	public function user_inactive($username)
	{
		$isinactive;

		$stmt = sprintf("SELECT active FROM login WHERE user = '%s'", $username);

		$result = $this->sql_conn->query($stmt);

		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			if ($row['active'] == 0)
				$isinactive = true;
			else
				$isinactive = false;
		}
		$result->close();

		return $isinactive;
	}
	public function user_activate($username)
	{
		$stmt = sprintf("UPDATE login SET active = 1 WHERE user = '%s'", $username);

		if (!$this->sql_conn->query($stmt))
			trigger_error('User::change_pass(): '.$this->sql_conn->error, E_USER_ERROR);
	}
	public function logoff()
	{
		$this->session_close();
	}
	private function set_error($errno)
	{
		switch ($errno)
		{
		case self::SESSION_INVALID:
			$this->error = 'La sesión actual es invalida';
			break;
		case self::USER_INVALID:
			$this->error = 'La cuenta de usario es invalida';
			break;
		case self::PASS_INVALID:
			$this->error = 'Su contraseña original no es correcta';
			break;
		}
	}
}
