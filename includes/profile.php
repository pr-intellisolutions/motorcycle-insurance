<?php
class Profile extends User
{
	// Error constants
	const BAD_INPUT = 0;
	const USER_TAKEN = 1;

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

	function __construct()
	{
		parent::__construct();
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

		$stmt = sprintf("INSERT INTO login(id, user, pass, email, role, regdate, ip, browser, session, passdate) 
			VALUES (%d, '%s', '%s', '%s', '%s', now(), '%s', '%s', '%s', adddate(now(), %d))",
			$this->user_id, $this->user, $this->pass, $this->email, $this->role,
			$_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], session_id(), $site_config->pass_expiration);

		if (!$this->sql_conn->query($stmt))
			trigger_error('Profile::create_account(): '.$this->sql_conn->error, E_USER_ERROR);

		$stmt = sprintf("INSERT INTO profile(userid, name, middle, last, maiden, phone, address1, address2, city, state, zip, country)
			VALUES (%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
			$this->user_id, $this->first, $this->middle, $this->last, $this->maiden, $this->phone, $this->address1, $this->address2,
			$this->city, $this->state, $this->zip, $this->country);

		if (!$this->sql_conn->query($stmt))
			trigger_error('Profile::create_account(): '.$this->sql_conn->error, E_USER_ERROR);

		if ($this->role === 'provider')
		{
			$stmt = sprintf("INSERT INTO providers(userid) VALUES (%d)", $this->user_id);
			
			if (!$this->sql_conn->query($stmt))
				trigger_error('Profile::create_account(): '.$this->sql_conn->error, E_USER_ERROR);
		}
		return true;
	}
	public function load_profile($user_id)
	{
		return true;
	}
	public function save_profile($user_id)
	{
		return true;
	}
	private function uid_available($user_id)
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
	private function user_available($username)
	{
		$stmt = sprintf("SELECT * FROM login WHERE user = '%s'", $username);

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
			case self::USER_TAKEN:
				$this->error = 'El usuario que escogió ya está registrado.';
				break;
		}
	}
}
