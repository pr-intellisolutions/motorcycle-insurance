<?php
require_once('../common.php');

if (!$user->auth())
{
	http_response_code(400);
	die();
}

if (!isset($_POST['service_id']) && !isset($_POST['type']) &&
	!isset($_POST['model']) && !isset($_POST['brand']) &&
	!isset($_POST['year']) && !isset($_POST['plate']) &&
	!isset($_POST['serial']))
{
	http_response_code(400);
	die();
}

if (empty($_POST['service_id']) || empty($_POST['type']) ||
	empty($_POST['model']) || empty($_POST['brand']) ||
	empty($_POST['year']) || empty($_POST['plate']) ||
	empty($_POST['serial']))
{
	echo "La información no pudo ser procesada correctamente";
	die();
}

// Check if vehicle is already registered
$stmnt = sprintf("SELECT * FROM vehicles WHERE userid=%d AND plate='%s'", $user->user_id, $_POST['plate']);

$result = $user->sql_conn->query($stmnt);

if ($result->num_rows > 0)
{
	echo "Tablilla de vehículo ya registrada.";
	
	$result->close();

	die();
}

// Check whenever user has exceeded maximum number of registered vehicles
$stmnt = sprintf("SELECT vehicle_counter, max_vehicles FROM services WHERE userid=%d AND id=%d", $user->user_id, $_POST['service_id']);

$result = $user->sql_conn->query($stmnt);

if ($result->num_rows > 0)
{
	$row = $result->fetch_assoc();
	
	if ($row['vehicle_counter'] >= $row['max_vehicles'])
	{
		echo "Usted ha excedido el máximo de vehículos que puede registrar bajo este plan de servicio.";
		
		$result->close();
		
		die();
	}
	else
	{
		// Add vehicle to service plan		
		$stmnt = sprintf("INSERT INTO vehicles(userid, service_id, type, model, brand, year, plate, serial) VALUES (%d, '%s', '%s', '%s', '%s', '%s', '%s', '%s')", 
			$user->user_id, $_POST['service_id'], $_POST['type'], $_POST['model'], $_POST['brand'], $_POST['year'], $_POST['plate'], $_POST['serial']);
		$user->sql_conn->query($stmnt);

		// Update vehicle counter
		$stmnt = sprintf("UPDATE services SET vehicle_counter = vehicle_counter + 1 WHERE id=%d", $_POST['service_id']);
		$user->sql_conn->query($stmnt);

		echo "success";

		die();
	}
}
?>
