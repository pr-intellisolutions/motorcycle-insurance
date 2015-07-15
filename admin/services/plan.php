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
		$this->name = $this->sanitize_input($plan['name']);
		
		/*$stmnt = sprintf("INSERT INTO plans(name, title, description, num_occurrences, num_miles, num_vehicles,
			plan_price, mile_price, extend_price, term) VALUES ('%s', '%s', '%s', %d, %d, %d, %f, %f, %f, %d)",
			$_POST['name'], $_POST['title'], $_POST['description'], $_POST['occurrences'], $_POST['miles'], $_POST['vehicles'], $_POST['plan_price'],
			$_POST['mile_price'], $_POST['extend_price'], $_POST['term']);
			
		if (!$user->sql_conn->query($stmnt))
			trigger_error('/admin/services/index.php::create_plan(): '.$user->sql_conn->error, E_USER_ERROR);*/

	}
}

?>
