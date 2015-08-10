<?php

class Provider extends User
{
	
	public $id;
	public $userid;
	public $username;
	public $companyName;
	public $companyPhone;
	public $companyEmail;
	public $area;
	public $CompanyAddress1;
	public $companyAddress2;
	public $city;
	public $zip;
	public $country;
	
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	public function add_provider($account)
	{
		if (!isset($account['user']) || $account['user'] === "" || !isset($account['companyName']) || $account['companyName'] === "")
		{
			$this->set_error(self::BAD_INPUT);
			return false;
		}
		
		$this->username = $this->sanitize_input($account['user']);

		if ($this->user_available($this->username))
		{
			$this->set_error(self::UNREGISTERED_USER);
			return false;
		}
		
		$this->companyName	 	= isset($account['companyName'])		? $this->sanitize_input($account['companyName']) : "";
		$this->area				= isset($account['area'])				? $this->sanitize_input($account['area']) : "";
		$this->companyPhone		= isset($account['companyPhone']) 		? $this->sanitize_input($account['companyPhone']) : "";
		$this->companyEmail		= isset($account['companyEmail']) 		? $this->sanitize_input($account['companyEmail']) : "";
		$this->companyAddress1	= isset($account['companyAddress1']) 	? $this->sanitize_input($account['companyAddress1']) : "";
		$this->companyAddress2	= isset($account['companyAddress2']) 	? $this->sanitize_input($account['companyAddress2']) : "";
		$this->city				= isset($account['city']) 				? $this->sanitize_input($account['city']) : "";
		$this->zip				= isset($account['zip']) 				? $this->sanitize_input($account['zip']) : "";
		$this->country			= isset($account['country']) 			? $this->sanitize_input($account['country']) : "";

		// Populate providers table 
		$stmt = sprintf("INSERT INTO providers (id, userid, profile_id, companyName, companyPhone, companyEmail, area, companyAddress1, companyAddress2, city, zip, country) 
			VALUES (NULL, (SELECT id FROM login WHERE user = '%s'), (SELECT id FROM profile WHERE userid = (SELECT id FROM login WHERE user = '%s')), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
			$this->username, $this->username, $this->companyName, $this->companyPhone, $this->companyEmail, $this->area, $this->companyAddress1, $this->companyAddress2, $this->city, $this->zip, $this->country);
		
		if (!$this->sql_conn->query($stmt))
			trigger_error('/admin/providers/index.php::add_provider(): '.$this->sql_conn->error, E_USER_ERROR);

		return true;
	}
	
	public function modify_provider($account)
	{
			
		if (!isset($account['username']) || $account['username'] === "" || !isset($account['companyName']) || $account['companyName'] === "" )
		{
			$this->set_error(self::BAD_INPUT);
			return false;
		}
		
		$this->username			= $this->sanitize_input($account['username']);
		$this->companyName		= $this->sanitize_input($account['companyName']);
		$this->area				= $this->sanitize_input($account['area']);
		
		
		if ($this->user_available($this->username))
		{
			$this->set_error(self::UNREGISTERED_USER);
			return false;
		}
				

		$this->companyPhone		= isset($account['companyPhone']) 		? $this->sanitize_input($account['companyPhone']) : "";
		$this->companyEmail		= isset($account['companyEmail']) 		? $this->sanitize_input($account['companyEmail']) : "";
		$this->companyAddress1	= isset($account['companyAddress1']) 	? $this->sanitize_input($account['companyAddress1']) : "";
		$this->companyAddress2	= isset($account['companyAddress2']) 	? $this->sanitize_input($account['companyAddress2']) : "";
		$this->city		= isset($account['city']) 						? $this->sanitize_input($account['city']) : "";
		$this->zip		= isset($account['zip']) 						? $this->sanitize_input($account['zip']) : "";
		$this->country	= isset($account['country']) 					? $this->sanitize_input($account['country']) : "";
		
			
		$stmnt = sprintf("UPDATE providers SET companyName='%s', companyPhone='%s', companyEmail='%s', area='%s', companyAddress1='%s', companyAddress2='%s',
			city='%s', zip='%s', country='%s' WHERE userid= (SELECT id FROM login WHERE user='%s')",
			$this->companyName, $this->companyPhone, $this->companyEmail, $this->area, $this->companyAddress1,
			$this->companyAddress2, $this->city, $this->zip, $this->country, $this->username);
			
		if (!$this->sql_conn->query($stmnt))
			trigger_error('/admin/providers/index.php::modify_provider(): '.$this->sql_conn->error, E_USER_ERROR);
	
		return true;
	}
	
	
	
}

?>
