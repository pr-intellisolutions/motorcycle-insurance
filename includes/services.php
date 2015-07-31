<?php

class Service extends User
{
	public $num_occurrences;
	public $num_miles;
	public $num_vehicles;
	
	function __construct()
	{
		parent::__construct();
	}
	function load_constraints($user_data)
	{
		if (isset($user_data['username']))
		{
			$stmnt = sprintf("SELECT user FROM login WHERE user='$'", $user_data['username']);
			
			$result = $this->sql_conn->query($stmnt);
			
			if ($result->num_rows > 0)
			{
				return true;
			}
			else
				$this->seterror(self::UNREGISTERED_USER);
		}
		else if (isset($user_data['member_id']))
		{
			// search by member_id
		}
		return true;
	}
}

?>
