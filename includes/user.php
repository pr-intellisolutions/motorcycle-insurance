<?php

class User extends Session
{
	// Error constants
	const BAD_INPUT 		= 0;
	const SESSION_INVALID	= 1;
	const USER_INVALID		= 2;
	const PASS_INVALID		= 3;
	const NEWPASS_REQUEST	= 4;
	const OLDPASS_EXPIRED	= 5;
	const USER_TAKEN		= 6;

	public $id;
	public $first;
	public $middle;
	public $last;
	public $maiden;
	public $phone;
	public $address1;
	public $address2;
	public $city;
	public $state;
	public $zip;
	public $country;

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
	public function create_account($account)
	{
		global $site_config;
	
		if (!isset($account['username']) && !isset($account['password']) && !isset($account['email']) && !isset($account['role']))
		{
			$this->set_error(self::BAD_INPUT);
			return false;
		}		
		// Initialize all mandatory fields
		$this->user		= $this->sanitize_input($account['username']);
		$this->pass		= $this->sanitize_input($account['password']);
		$this->email	= $this->sanitize_input($account['email']);
		$this->role		= $this->sanitize_input($account['role']);

		$this->first	= $this->sanitize_input($account['first']);
		$this->middle	= $this->sanitize_input($account['middle']);
		$this->last		= $this->sanitize_input($account['last']);
		$this->maiden	= $this->sanitize_input($account['maiden']);
		$this->address1	= $this->sanitize_input($account['address1']);
		$this->address2	= $this->sanitize_input($account['address2']);
		$this->city		= $this->sanitize_input($account['city']);
		$this->state	= $this->sanitize_input($account['state']);
		$this->zip		= $this->sanitize_input($account['zip']);
		$this->country	= $this->sanitize_input($account['country']);
		$this->phone	= $this->sanitize_input($account['telephone']);
		$this->perms	= $this->role === 'admin' ? 'all' : 'none';

		// Check if user is available
		if (!$this->user_available($this->user))
		{
			$this->set_error(self::USER_TAKEN);
			return false;
		}

		// Generate a secure user id
		do
		{
			$bytes = openssl_random_pseudo_bytes(2);

			$secure_id = hexdec(bin2hex($bytes));

			$this->user_id = $secure_id;
		}
		while(!$this->uid_available($this->user_id));

		// Encrypt password
		$options = array('cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));
	
		$this->pass = password_hash($this->pass, PASSWORD_BCRYPT, $options);

		// Create profile
		$stmt = sprintf("INSERT INTO login(id, user, pass, email, role, regdate, ip, browser, active, session, passdate, permissions) 
			VALUES (%d, '%s', '%s', '%s', '%s', now(), '%s', '%s', 1, '%s', adddate(now(), %d), '%s')",
			$this->user_id, $this->user, $this->pass, $this->email, $this->role, $_SERVER['REMOTE_ADDR'],
			$_SERVER['HTTP_USER_AGENT'], session_id(), $site_config->pass_expiration, $this->perms);

		if (!$this->sql_conn->query($stmt))
			trigger_error('Profile::create_account(): '.$this->sql_conn->error, E_USER_ERROR);

		$stmt = sprintf("INSERT INTO profile(userid, name, middle, last, maiden, phone, address1, address2, city, state, zip, country)
			VALUES (%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
			$this->user_id, $this->first, $this->middle, $this->last, $this->maiden, $this->phone, $this->address1, $this->address2,
			$this->city, $this->state, $this->zip, $this->country);

		if (!$this->sql_conn->query($stmt))
			trigger_error('Profile::create_account(): '.$this->sql_conn->error, E_USER_ERROR);

		return true;
	}
	public function delete_account()
	{
		// delete account
	}
	public function uid_available($user_id)
	{
		$stmt = sprintf("SELECT * FROM login WHERE id = %d", $user_id);

		$result = $this->sql_conn->query($stmt);

		if ($result->num_rows > 0)
		{
			$this->set_error(self::UID_TAKEN);
			return false;
		}

		$result->close();

		return true;
	}
	public function user_available($username)
	{
		$stmt = sprintf("SELECT user FROM login WHERE user = '%s'", $username);

		$result = $this->sql_conn->query($stmt);

		if ($result->num_rows > 0)
		{
			$this->set_error(self::USER_TAKEN);
			return false;
		}

		$result->close();

		return true;
	}
	private function set_error($errno)
	{
		switch ($errno)
		{
		case self::BAD_INPUT:
			$this->error = 'La información de entrada no se pudo leer correctamente';
			break;	
		case self::SESSION_INVALID:
			$this->error = 'La sesión actual es invalida';
			break;
		case self::USER_INVALID:
			$this->error = 'La cuenta de usario es invalida';
			break;
		case self::PASS_INVALID:
			$this->error = 'Su contraseña original no es correcta';
			break;			
		case self::USER_TAKEN:
			$this->error = 'El usuario que escogió ya está registrado.';
			break;
		}
	}
}
