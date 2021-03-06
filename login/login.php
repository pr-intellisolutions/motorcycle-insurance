<?php
class Login extends User
{
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
	public function set_remember_me($user)
	{
		$user = $this->sanitize_input($user);

		$token = sha1(openssl_random_pseudo_bytes(256));

		setcookie("username", $user, time() + 86400, "/"); // set to last a day
		setcookie("token", $token, time() + 86400, "/"); // set to last a day

		$stmt = sprintf("UPDATE login SET token='%s' WHERE user='%s'", $token, $user);

		if (!$this->sql_conn->query($stmt))
			trigger_error('Login::valid(): '.$this->sql_conn->error, E_USER_ERROR);

		
	}
}
?>