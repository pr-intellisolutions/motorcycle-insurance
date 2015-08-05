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
	public function save_profile($user_id)
	{
		
	}
}
?>