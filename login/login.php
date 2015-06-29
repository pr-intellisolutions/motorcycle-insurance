<?php
class Login extends User
{
	// Error constants
	const LOGIN_EXPIRED		= 0;
	const LOGIN_DISABLED	= 1;
	const LOGIN_INVALID		= 2;

	public $error;

	function __construct()
	{
		// Start a new session
		parent::__construct();
	}
	public function validate_user($user, $pass)
	{
		global $site_config;
		// Remove unwanted or malicious user input
		$this->user = $this->sanitize_input($user);
		$this->pass = $this->sanitize_input($pass);

		$stmnt = sprintf("SELECT * FROM login WHERE user = '%s'", $this->user);

		$result = $this->sql_conn->query($stmnt);

		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();

			if ($row['expired'])
				$this->set_error(self::LOGIN_EXPIRED);

			else if ($row['disabled'])
				$this->set_error(self::LOGIN_DISABLED);

			else if ($site_config->max_login_attempts > 0 && $row['login_attempts'] >= $site_config->max_login_attempts)
			{
				$stmt = sprintf("UPDATE login SET disabled = 1 WHERE user = '%s'", $this->user);

				if ($this->sql_conn->query($stmt))
					$this->set_error(self::LOGIN_DISABLED);
				else
					trigger_error('Login::valid(): '.$this->sql_conn->error, E_USER_ERROR);
			}
			else if ($site_config->pass_expiration > 0 && $this->pass_expired($row['passdate']))
			{
				$stmt = sprintf("UPDATE login SET passchg = 1 WHERE user = '%s'", $this->user);

				if ($this->sql_conn->query($stmt))
					$this->passchg = User::OLDPASS_EXPIRED;
				else
					trigger_error('Login::valid(): '.$this->sql_conn->error, E_USER_ERROR);
			}
			else if (password_verify($this->pass, $row['pass']))
			{
				if ($row['passchg'])
					$this->passchg = User::OLDPASS_EXPIRED;
				else
				{
					$stmt = sprintf("UPDATE login SET lastvisit = now(), lastip = ip, lastbrowser = browser, ip = '%s', browser = '%s', session = '%s', login_attempts = %d WHERE user = '%s'",
						$_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], session_id(), 0, $this->user);

					if (!$this->sql_conn->query($stmt))
						trigger_error('Login::valid(): '.$this->sql_conn->error, E_USER_ERROR);
				}
			}
			else
			{
				$stmt = sprintf("UPDATE login SET login_attempts = login_attempts + 1 WHERE user = '%s'", $this->user);

				if (!$this->sql_conn->query($stmt))
					trigger_error('Login::valid(): '.$this->sql_conn->error, E_USER_ERROR);

				$this->set_error(self::LOGIN_INVALID);
			}
			$this->session_init($row);

			$result->close();
		}
		else
			$this->set_error(self::LOGIN_INVALID);

		if ($this->error)
			return false;
		else
			return true;
	}
	public function pass_expired($pass_date)
	{
		if (strtotime(date('Y-m-d G:i:s')) > strtotime($pass_date))
			return true;
		else
			return false;
	}
	private function set_error($errno)
	{
		switch ($errno)
		{
		case self::LOGIN_EXPIRED:
			$this->error = 'Su cuenta ha expirado contacte a su administrador para renovaci&oacute;n';
			break;
		case self::LOGIN_DISABLED:
			$this->error = 'Su cuenta ha sido desabilitada contacte a su administrador para reactivaci&oacute;n';
			break;
		case self::LOGIN_INVALID:
			$this->error = 'La combinaci&oacute;n de usuario y contrase&ntilde;a es incorrecto';
			break;
		}
	}
}
?>