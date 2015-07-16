<?php
class Session extends SiteDB
{
	// Error constants
	const BAD_INPUT 		= 0;
	const SESSION_INVALID	= 1;
	const USER_INVALID		= 2;
	const PASS_INVALID		= 3;
	const NEWPASS_REQUEST	= 4;
	const OLDPASS_EXPIRED	= 5;
	const USER_TAKEN		= 6;
	const PLAN_TAKEN		= 7;
	const LOGIN_EXPIRED		= 8;
	const LOGIN_DISABLED	= 9;
	const LOGIN_INVALID		= 10;
	const UNREGISTERED_USER		= 11;

	public $session_id;
	public $user_id;
	public $user;
	public $pass;
	public $email;
	public $role;
	public $passchg;
	public $perms;

	public $error;

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
	public function set_error($errno)
	{
		switch ($errno)
		{
		case self::BAD_INPUT:
			$this->error = 'La información de entrada no se pudo leer correctamente.';
			break;	
		case self::SESSION_INVALID:
			$this->error = 'La sesión actual es invalida.';
			break;
		case self::USER_INVALID:
			$this->error = 'La cuenta de usario es invalida.';
			break;
		case self::PASS_INVALID:
			$this->error = 'Su contraseña original no es correcta.';
			break;			
		case self::USER_TAKEN:
			$this->error = 'Este usuario ya está registrado.';
			break;
		case self::PLAN_TAKEN:
			$this->error = 'Este plan ya está registrado.';
			break;
		case self::LOGIN_EXPIRED:
			$this->error = 'Su cuenta ha expirado contacte a su administrador para renovación.';
			break;
		case self::LOGIN_DISABLED:
			$this->error = 'Su cuenta ha sido desabilitada contacte a su administrador para reactivación.';
			break;
		case self::LOGIN_INVALID:
			$this->error = 'La combinación de usuario y contraseña es incorrecto.';
			break;
		case self::UNREGISTERED_USER:
			$this->error = 'Usuario no registrado.';
			break;
		}
	}
}
