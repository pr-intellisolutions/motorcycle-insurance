<?php
class Session extends SiteDB
{
	public $session_id;
	public $user_id;
	public $user;
	public $pass;
	public $email;
	public $role;
	public $passchg;
	public $perms;

	function __construct()
	{
		// Open the connection to the database
		parent::__construct();

		// Start or resume user session
		if (session_status() == PHP_SESSION_NONE)
			session_start();

		$this->session_id	= (isset($_SESSION['session_id'])) ? $this->sanitize_input($_SESSION['session_id']) : 0;
		$this->user_id		= (isset($_SESSION['user_id'])) ? $this->sanitize_input($_SESSION['user_id']) : 0;
		$this->user			= (isset($_SESSION['user'])) ? $this->sanitize_input($_SESSION['user']) : "";
		$this->pass			= (isset($_SESSION['pass'])) ? $this->sanitize_input($_SESSION['pass']) : "";
		$this->email		= (isset($_SESSION['email'])) ? $this->sanitize_input($_SESSION['email']) : "";
		$this->role			= (isset($_SESSION['role'])) ? $this->sanitize_input($_SESSION['role']) : "";
		$this->passchg		= (isset($_SESSION['passchg'])) ? $this->sanitize_input($_SESSION['passchg']) : 0;
		$this->perms		= (isset($_SESSION['perms'])) ? $this->sanitize_input($_SESSION['perms']) : "";

	}
	public function session_init($val)
	{
		// Based on values extracted from the login database
		$_SESSION['session_id']	= session_id();
		$_SESSION['user_id']	= $val['id'];
		$_SESSION['user']		= $val['user'];
		$_SESSION['pass']		= $val['pass'];
		$_SESSION['email']		= $val['email'];
		$_SESSION['role']		= $val['role'];
		$_SESSION['passchg']	= $val['passchg'];
		$_SESSION['perms']		= $val['permissions'];
	}
	public function session_update($key, $val)
	{
		$_SESSION[$key] = $val;
	}
	public function session_close()
	{
		if (session_status() == PHP_SESSION_ACTIVE)
		{
			session_unset();
			session_destroy();
		}
	}
}
