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

	function __construct()
	{
		parent::__construct();
	
		$stmnt = sprintf("SELECT * FROM config WHERE id=1");
		
		$result = $this->sql_conn->query($stmnt);
	
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			
			$this->site_name = $row['site_name'];
			$this->site_desc = $row['site_desc'];
			$this->site_host = $row['site_host'];
			$this->site_module = $row['site_module'];
			$this->user_minlen = $row['user_minlen'];
			$this->user_maxlen = $row['user_maxlen'];
			$this->user_complexity = $row['user_complexity'];
			$this->pass_minlen = $row['pass_minlen'];
			$this->pass_maxlen = $row['pass_maxlen'];
			$this->pass_complexity = $row['pass_complexity'];
			$this->pass_expiration = $row['pass_expiration'];
			$this->max_login_attempts = $row['max_login_attempts'];
			$this->activation_req = $row['activation_req'];
		}
		else
			trigger_error('SiteConfig::__construct(): '.$this->sql_conn->error, E_USER_ERROR);

		$result->close();
	}
}
?>
