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
		if (!isset($account['user']) || $account['user'] === "")
		{
			$this->set_error(self::BAD_INPUT);
			return false;
		}
		
		$this->username 	= $this->sanitize_input($account['user']);

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
		$stmt = sprintf("INSERT INTO providers (userid, profile_id, companyName, companyPhone, companyEmail, area, companyAddress1, companyAddress2, city, zip, country) 
			VALUES ((SELECT login.id FROM login WHERE login.user = '%s'), (SELECT id FROM profile WHERE profile.userid = (SELECT id FROM login WHERE user = '%s')), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
			$this->username, $this->username, $this->companyName, $this->companyPhone, $this->companyEmail, $this->area, $this->companyAddress1, $this->companyAddress2, $this->city, $this->zip, $this->country);
		
		if (!$this->sql_conn->query($stmt))
		{
			$this->error = $this->sql_conn->error;
			return false;
		}
			
		if (!$this->update_role($this->username))
		{
			$this->set_error(self::INCOMPLETE_TRANSACTION);
			return false;
		}

		return true;
	}
		
	public function load_provider($id)
	{
		$id = $this->sanitize_input($id);

		if ($this->id_available($id) == false)
		{
			$stmnt = sprintf("SELECT * FROM providers WHERE id=%d", $id);
		
			$result = $this->sql_conn->query($stmnt);
		
			if ($result->num_rows > 0)
			{
				return $result->fetch_assoc();
			}
			else
			{
				$this->set_error(self::BAD_INPUT);
				return NULL;
			}
		}
		else
		{
			$this->set_error(self::UNREGISTERED_PROVIDER);
			return NULL;
		}
	}
	
	public function modify_provider($account)
	{
			
		if (!isset($account['COMPANYNAME']) || $account['COMPANYNAME'] === "" || !isset($account['ID']) || $account['ID'] === "" )
		{
			$this->set_error(self::BAD_INPUT);
			return false;
		}
		$this->id				= $this->sanitize_input($account['ID']);
		$this->userid			= $this->sanitize_input($account['USERID']);
		$this->companyName		= $this->sanitize_input($account['COMPANYNAME']);
		$this->area				= $this->sanitize_input($account['AREA']);
		
		
		if ($this->userid_available($this->userid))
		{
			$this->set_error(self::UNREGISTERED_USER);
			return false;
		}

		$this->companyPhone		= isset($account['COMPANYPHONE']) 		? $this->sanitize_input($account['COMPANYPHONE']) : "";
		$this->companyEmail		= isset($account['COMPANYEMAIL']) 		? $this->sanitize_input($account['COMPANYEMAIL']) : "";
		$this->companyAddress1	= isset($account['COMPANYADDRESS1']) 	? $this->sanitize_input($account['COMPANYADDRESS1']) : "";
		$this->companyAddress2	= isset($account['COMPANYADDRESS2']) 	? $this->sanitize_input($account['COMPANYADDRESS2']) : "";
		$this->city		= isset($account['CITY']) 						? $this->sanitize_input($account['CITY']) : "";
		$this->zip		= isset($account['ZIP']) 						? $this->sanitize_input($account['ZIP']) : "";
		$this->country	= isset($account['COUNTRY']) 					? $this->sanitize_input($account['COUNTRY']) : "";
		
			
		$stmnt = sprintf("UPDATE providers SET userid=%d,companyName='%s', companyPhone='%s', companyEmail='%s', area='%s', companyAddress1='%s', companyAddress2='%s',
			city='%s', zip='%s', country='%s' WHERE id=%d",
			$this->userid, $this->companyName, $this->companyPhone, $this->companyEmail, $this->area, $this->companyAddress1,
			$this->companyAddress2, $this->city, $this->zip, $this->country, $this->id);
			
		if (!$this->sql_conn->query($stmnt))
			trigger_error('/admin/providers/index.php::modify_provider(): '.$this->sql_conn->error, E_USER_ERROR);
	
		if (!$this->update_userid_role($this->userid))
		{
			$this->set_error(self::INCOMPLETE_TRANSACTION);
			return false;
		}
	
		return true;
	}
	
	public function delete_provider($account)
	{
		$this->id= $this->sanitize_input($account['ID']);
		$confirm_delete;

		if ($this->id_available($this->id) == false)
		{
			if ($this->check_other_accounts($this->id) == false)
			{
				$stmnt = sprintf("UPDATE login SET role='user' WHERE id=(SELECT userid FROM providers WHERE id=%d", $this->id);
				$this->sql_conn->query($stmnt);
			}
			
			$stmnt = sprintf("DELETE FROM providers WHERE id='%d'", $this->id);
			$this->sql_conn->query($stmnt);

			if ($this->id_available($this->id) == true)
			{						
					return true;
			}
			else 
			{
					$this->set_error(self::INCOMPLETE_TRANSACTION);
					return false;
			}
		}
		else
		{
			$this->set_error(self::BAD_INPUT);
			return true;
		}
	}

	public function check_role($user)
	{
		$user = $this->sanitize_input($user);
		$confirm_role;
		
		$stmnt = sprintf("SELECT * FROM login WHERE role='provider' and user='%s'", $user);		
		$result = $this->sql_conn->query($stmnt);

		if ($result->num_rows > 0)
		{
			$confirm_role = true;
		}
		else 
		{
			$confirm_role = false; 
		}	
				
		$result->close();
		return $confirm_role;

	}
	
		public function check_userid_role($userid)
	{
		$userid = $this->sanitize_input($userid);
		$confirm_role;
		
		$stmnt = sprintf("SELECT * FROM login WHERE role='provider' and id=%d", $userid);		
		$result = $this->sql_conn->query($stmnt);

		if ($result->num_rows > 0)
		{
			$confirm_role = true;
		}
		else 
		{
			$confirm_role = false; 
		}	
				
		$result->close();
		return $confirm_role;

	}
	
	public function update_role($user)
	{
		$user = $this->sanitize_input($user);
		$confirm_role;

		if ($this->check_role($user) == true)
		{
			$confirm_role= true;
		}
		else 
		{
			$stmnt = sprintf("UPDATE login SET role='provider' WHERE user='%s'", $user);
			$this->sql_conn->query($stmnt);
		
			if ($this->check_role($user) == true)
				$confirm_role= true;
			else
				$confirm_role = false;
		}

		return $confirm_role;
	}
	
		public function update_userid_role($userid)
	{
		$userid = $this->sanitize_input($userid);
		$confirm_role;

		if ($this->check_userid_role($userid) == true)
		{
			$confirm_role= true;
		}
		else 
		{
			$stmnt = sprintf("UPDATE login SET role='provider' WHERE id=%d", $userid);
			$this->sql_conn->query($stmnt);
		
			if ($this->check_userid_role($userid) == true)
				$confirm_role= true;
			else
				$confirm_role = false;
		}

		return $confirm_role;
	}
	
	
	public function id_available($id)
	{
		$id = $this->sanitize_input($id);
		$confirm_id;
		
		$stmnt = sprintf("SELECT * FROM providers WHERE id='%d'", $id);		
		$result = $this->sql_conn->query($stmnt);

		if ($result->num_rows > 0)
		{
			$confirm_id = false;
		}
		else 
		{
			$confirm_id = true; 
		}	
				
		$result->close();
		return $confirm_id;

	}
	
		public function userid_available($userid)
	{
		$userid = $this->sanitize_input($userid);
		$confirm_userid;
		
		$stmnt = sprintf("SELECT * FROM login WHERE id=%d", $userid);		
		$result = $this->sql_conn->query($stmnt);

		if ($result->num_rows > 0)
		{
			$confirm_id = false;
		}
		else 
		{
			$confirm_id = true; 
		}	
				
		$result->close();
		return $confirm_id;

	}
	
	public function check_other_accounts($id)
	{
		$id = $this->sanitize_input($id);
		$multiple_accounts;
		
		$stmnt = sprintf("SELECT * FROM providers WHERE userid= (SELECT userid FROM providers WHERE id=%d)", $id);		
		$result = $this->sql_conn->query($stmnt);

		if ($result->num_rows == 1)
		{
			$multiple_accounts = false;
		}
		else 
		{
			$multiple_accounts = true; 
		}	
				
		$result->close();
		return $multiple_accounts;

	}
	
}

?>
