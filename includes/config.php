<?php
class SiteConfig extends SiteDB
{
	public $site_name;
	public $site_desc;
	public $site_host;
	public $site_module;

	public $user_minlen;
	public $user_maxlen;
	public $user_complexity;

	public $pass_minlen;
	public $pass_maxlen;
	public $pass_complexity;
	public $pass_expiration;

	public $max_login_attempts;
	public $activation_req;

	public $error;

	function __construct()
	{
		parent::__construct();
	
		$stmnt = sprintf("SELECT * FROM config WHERE id=1");
		
		$result = $this->sql_conn->query($stmnt);
	
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			
			$this->site_name			= $row['site_name'];
			$this->site_desc			= $row['site_desc'];
			$this->site_host			= $row['site_host'];
			$this->site_module			= $row['site_module'];
			$this->user_minlen			= $row['user_minlen'];
			$this->user_maxlen			= $row['user_maxlen'];
			$this->user_complexity		= $row['user_complexity'];
			$this->pass_minlen			= $row['pass_minlen'];
			$this->pass_maxlen			= $row['pass_maxlen'];
			$this->pass_complexity		= $row['pass_complexity'];
			$this->pass_expiration		= $row['pass_expiration'];
			$this->max_login_attempts	= $row['max_login_attempts'];
			$this->activation_req		= $row['activation_req'];
			
			$result->close();
		}
		else
			trigger_error('SiteConfig::__construct(): '.$this->sql_conn->error, E_USER_ERROR);
	}
	
	public function save_config($config_data)
	{
		$this->site_name	= isset($config_data['siteName']) ? $this->sanitize_input($config_data['siteName']) : "";
		$this->site_desc	= isset($config_data['siteDesc']) ? $this->sanitize_input($config_data['siteDesc']) : "";
		$this->site_host	= isset($config_data['siteHost']) ? $this->sanitize_input($config_data['siteHost']) : "";
		$this->site_module	= isset($config_data['siteModule']) ? $this->sanitize_input($config_data['siteModule']) : "";

		$stmt = sprintf("UPDATE config SET site_name = '%s', site_desc = '%s', site_host = '%s', site_module = '%s'",
			$this->site_name, $this->site_desc, $this->site_host, $this->site_module);

		if (!$this->sql_conn->query($stmt))
		{
			$this->error = $this->sql_conn->error;
			return false;
		}
		return true;
	}
	
	public function save_security_policy($sec_config_data)
	{
		$this->user_minlen			= isset($sec_config_data['userMinLen']) ? $this->sanitize_input($sec_config_data['userMinLen']) : "";
		$this->user_maxlen			= isset($sec_config_data['userMaxLen']) ? $this->sanitize_input($sec_config_data['userMaxLen']) : "";
		$this->user_complexity		= isset($sec_config_data['userComplexity']) ? $this->sanitize_input($sec_config_data['userComplexity']) : "";
		$this->pass_minlen			= isset($sec_config_data['passMinLen']) ? $this->sanitize_input($sec_config_data['passMinLen']) : "";
		$this->pass_maxlen			= isset($sec_config_data['passMaxLen']) ? $this->sanitize_input($sec_config_data['passMaxLen']) : "";
		$this->pass_complexity		= isset($sec_config_data['passComplexity']) ? $this->sanitize_input($sec_config_data['passComplexity']) : "";
		$this->pass_expiration		= isset($sec_config_data['passExpiration']) ? $this->sanitize_input($sec_config_data['passExpiration']) : "";
		$this->max_login_attempts	= isset($sec_config_data['maxLoginAttempts']) ? $this->sanitize_input($sec_config_data['maxLoginAttempts']) : "";
		$this->activation_req		= isset($sec_config_data['activationReq']) ? $this->sanitize_input($sec_config_data['activationReq']) : "";

		$stmt = sprintf("UPDATE config SET user_minlen = %d, user_maxlen = %d, user_complexity = '%s', pass_minlen = %d, pass_maxlen = %d,
			pass_complexity = '%s', pass_expiration = %d, max_login_attempts = %d, activation_req = %d",
			$this->user_minlen, $this->user_maxlen, $this->user_complexity, $this->pass_minlen, $this->pass_maxlen,
			$this->pass_complexity, $this->pass_expiration, $this->max_login_attempts, $this->activation_req);

		if (!$this->sql_conn->query($stmt))
		{
			$this->error = $this->sql_conn->error;
			return false;
		}
		return true;
	}
	
}
?>
