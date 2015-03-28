<?php
class SiteDB extends SiteConfig
{
	public $sql_conn;

	function __construct()
	{
		// Initialize connection database variables
		parent::__construct();

		// Create connection
		if (!isset($this->sql_conn))
			$this->sql_conn = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name, $this->db_port);

		// Check connection
		if ($this->sql_conn->connect_error)
			trigger_error('SiteDB::__construct(): '.$this->sql_conn->connect_error, E_USER_ERROR);
	}
	function __destruct()
	{
		if (isset($this->sql_conn))
			$this->sql_conn->close();
	}
	function sanitize_input($input_str)
	{
		// This function removes any malicious input a user might have used to compromise the system
		$input_str = stripslashes(htmlentities(strip_tags($input_str)));

		return $this->sql_conn->real_escape_string($input_str);
	}
}
?>