<?php

class Profile extends User
{
	function __construct()
	{
		parent::__construct();
	}
	public function load_profile($user_id)
	{
		return true;
	}
	public function save_profile($user_id)
	{
		return true;
	}
}
?>