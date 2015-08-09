<?php

class Profile extends User
{
	function __construct()
	{
		parent::__construct();
	}
	public function load_profile($user_id)
	{
		$stmnt = sprintf("SELECT * FROM profile INNER JOIN login ON profile.userid = login.id WHERE login.id = %d", $user_id);

		$result = $this->sql_conn->query($stmnt);
		
		if ($result->num_rows)
		{
			$row = $result->fetch_assoc();
			
			$this->first	= $row['first'];
			$this->middle	= $row['middle'];
			$this->last		= $row['last'];
			$this->maiden	= $row['maiden'];
			$this->phone	= $row['phone'];
			$this->address1	= $row['address1'];
			$this->address2 = $row['address2'];
			$this->city		= $row['city'];
			$this->state	= $row['state'];
			$this->zip		= $row['zip'];
			$this->country	= $row['country'];
			
		}
		else
			$this->set_error(self::UNREGISTERED_USER);
		
		$result->close();
	}
	public function save_profile($user_id, $profile_data)
	{
		$stmnt = sprintf("UPDATE profile SET first='%s', middle='%s', last='%s', maiden='%s', phone='%s', 
			address1='%s', address2='%s', city='%s', state='%s', zip='%s', country='%s' WHERE userid=%d",
			$profile_data['first'], $profile_data['middle'], $profile_data['last'], $profile_data['maiden'], 
			$profile_data['phone'], $profile_data['address1'], $profile_data['address2'], $profile_data['city'], 
			$profile_data['state'], $profile_data['zip'], $profile_data['country'], $user_id);
			
		if ($this->sql_conn->query($stmnt))
		{
			$stmnt = sprintf("UPDATE login SET email='%s' WHERE id=%d", $profile_data['email'], $user_id);
			
			if ($this->sql_conn->query($stmnt))
			{
				return true;
			}
		}	

		$this->error = $this->sql_conn->error;

		return false;
	}
}
?>