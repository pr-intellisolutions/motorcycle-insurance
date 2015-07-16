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
			plan_price, mile_price, extend_price, term) VALUES ('%s', '%s', '%s', %d, %d, %d, %f, %f, %f, %d)",
			$this->name, $this->title, $this->desc, $this->occurrences, $this->miles, $this->vehicles,
			$this->plan_price, $this->mile_price, $this->extend_price, $this->term);
			
		if (!$this->sql_conn->query($stmnt))
			trigger_error('/admin/services/index.php::create_plan(): '.$this->sql_conn->error, E_USER_ERROR);
	
		return true;
	}
	public function delete_plan($plan_name)
	{
		$plan_name = $this->sanitize_input($plan_name);

		$stmnt = sprintf("DELETE FROM plans WHERE name='%s'", $plan_name);
		
		if (!$this->sql_conn->query($stmnt))
			trigger_error('/admin/services/index.php::create_plan(): '.$this->sql_conn->error, E_USER_ERROR);
		
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
}

?>
