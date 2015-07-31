<?php

//example
$json_data = array('status' => 'success', 'name' => 'Dennis J. Borrero', 'error' => 'Invalid Input');

require_once('../../common.php');
require_once('../../includes/services.php');

if (!isset($_POST['search_input']) || $_POST['search_input'] === "")
{
	$json_data['status'] = 'failed';
	$json_data['error'] = 'El campo de busqueda esta vacio';
	echo json_encode($json_data);
	die();
}
?>
