<?php

class Plan extends User
{
	public $name;
	public $title;
	public $desc;
	public $occurrences;
	public $miles;
	public $vehicles;
	public $plan_price;
	public $mile_price;
	public $extend_price;
	public $term;
	
	function __construct()
	{
		parent::__construct();
	}
	public function create_plan($plan)
	{
		$active = 1;
	
		if (!isset($plan['plan_name']) || $plan['plan_name'] === "")
		{
			$this->set_error(self::BAD_INPUT);
			return false;
		}
		
		$this->name = $this->sanitize_input($plan['plan_name']);
		
		if (!$this->name_available($this->name))
		{
			$this->set_error(self::PLAN_TAKEN);
			return false;
		}

		if ($this->slot_available() >= 3)
			$active = 0;
	
		$this->title		= isset($plan['plan_title']) ? $this->sanitize_input($plan['plan_title']) : "";
		$this->desc			= isset($plan['plan_desc']) ? $this->sanitize_input($plan['plan_desc']) : "";
		$this->occurrences	= isset($plan['plan_occur']) ? $this->sanitize_input($plan['plan_occur']) : 0;
		$this->miles		= isset($plan['plan_mile']) ? $this->sanitize_input($plan['plan_mile']) : 0;
		$this->vehicles		= isset($plan['plan_vehicle']) ? $this->sanitize_input($plan['plan_vehicle']) : 0;
		$this->plan_price	= isset($plan['plan_price']) ? $this->sanitize_input($plan['plan_price']) : 0.0;
		$this->mile_price	= isset($plan['mile_price']) ? $this->sanitize_input($plan['mile_price']) : 0.0;
		$this->extend_price	= isset($plan['extend_price']) ? $this->sanitize_input($plan['extend_price']) : 0.0;
		$this->term			= isset($plan['plan_term']) ? $this->sanitize_input($plan['plan_term']) : 0;
		
		$stmnt = sprintf("INSERT INTO plans(name, title, description, num_occurrences, num_miles, num_vehicles,
			plan_price, mile_price, extend_price, term, active, date_entered) VALUES ('%s', '%s', '%s', %d, %d, %d, %f, %f, %f, %d, %d, now())",
			$this->name, $this->title, $this->desc, $this->occurrences, $this->miles, $this->vehicles,
			$this->plan_price, $this->mile_price, $this->extend_price, $this->term, $active);
			
		if (!$this->sql_conn->query($stmnt))
			trigger_error('/admin/services/index.php::create_plan(): '.$this->sql_conn->error, E_USER_ERROR);
	
		return true;
	}
	public function load_plan($plan_name)
	{
		$plan_name = $this->sanitize_input($plan_name);

		if ($this->name_available($plan_name) == false)
		{
			$stmnt = sprintf("SELECT * FROM plans WHERE name='%s'", $plan_name);
		
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
			$this->set_error(self::UNREGISTERED_PLAN);
			return NULL;
		}
	}
	public function save_plan($plan)
	{
		if (!isset($plan['plan_name']) || $plan['plan_name'] === "")
		{
			$this->set_error(self::BAD_INPUT);
			return false;
		}
		
		$this->name			= $this->sanitize_input($plan['plan_name']);
		$this->title		= isset($plan['plan_title']) ? $this->sanitize_input($plan['plan_title']) : "";
		$this->desc			= isset($plan['plan_desc']) ? $this->sanitize_input($plan['plan_desc']) : "";
		$this->occurrences	= isset($plan['plan_occur']) ? $this->sanitize_input($plan['plan_occur']) : 0;
		$this->miles		= isset($plan['plan_mile']) ? $this->sanitize_input($plan['plan_mile']) : 0;
		$this->vehicles		= isset($plan['plan_vehicle']) ? $this->sanitize_input($plan['plan_vehicle']) : 0;
		$this->plan_price	= isset($plan['plan_price']) ? $this->sanitize_input($plan['plan_price']) : 0.0;
		$this->mile_price	= isset($plan['mile_price']) ? $this->sanitize_input($plan['mile_price']) : 0.0;
		$this->extend_price	= isset($plan['extend_price']) ? $this->sanitize_input($plan['extend_price']) : 0.0;
		$this->term			= isset($plan['plan_term']) ? $this->sanitize_input($plan['plan_term']) : 0;
		
		$stmnt = sprintf("UPDATE plans SET title='%s', description='%s', num_occurrences=%d, num_miles=%d, num_vehicles=%d,
			plan_price=%f, mile_price=%f, extend_price=%f, term=%d, last_modify=now() WHERE name='%s'",
			$this->title, $this->desc, $this->occurrences, $this->miles, $this->vehicles,
			$this->plan_price, $this->mile_price, $this->extend_price, $this->term, $this->name);
			
		if (!$this->sql_conn->query($stmnt))
			trigger_error('/admin/services/index.php::save_plan(): '.$this->sql_conn->error, E_USER_ERROR);
	
		return true;
	}
	public function delete_plan($plan_name)
	{
		$plan_name = $this->sanitize_input($plan_name);

		$stmnt = sprintf("DELETE FROM plans WHERE name='%s'", $plan_name);

		$this->sql_conn->query($stmnt);

		if (!$this->sql_conn->query($stmnt))
			trigger_error('/admin/services/index.php::delete_plan(): '.$this->sql_conn->error, E_USER_ERROR);

		return true;
	}
	public function name_available($name)
	{
		$name = $this->sanitize_input($name);
		$is_available;
		
		$stmnt = sprintf("SELECT name FROM plans WHERE name='%s'", $name);
		
		$result = $this->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
			$is_available = false;
		else
			$is_available = true;
		
		$result->close();

		return $is_available;
	}
	public function activate_plan($name, $active)
	{
		$name = $this->sanitize_input($name);

		if ($active)
		{
			if ($this->slot_available() >= 3)
			{
				$this->set_error(self::NO_SLOTS_AVAILABLE);
				return false;
			}
			$stmnt = sprintf("UPDATE plans SET active=1 WHERE name='%s'", $name);

			if (!$this->sql_conn->query($stmnt))
				trigger_error('/admin/services/index.php::activate_plan(): '.$this->sql_conn->error, E_USER_ERROR);
		}
		else
		{
			$stmnt = sprintf("UPDATE plans SET active=0 WHERE name='%s'", $name);

			if (!$this->sql_conn->query($stmnt))
				trigger_error('/admin/services/index.php::activate_plan(): '.$this->sql_conn->error, E_USER_ERROR);
		}
		return true;
	}
	public function slot_available()
	{
		$count = 0;
	
		$stmnt = sprintf("SELECT active FROM plans WHERE active=1");
	
		$result = $this->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
				$count++;
		}
		return $count;
	}
}

?>
