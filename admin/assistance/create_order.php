<?php

require_once('../../common.php');

if ($user->auth() && $user->role === 'admin')
{
	if (empty($_POST['member_id']))
	{
		echo "El número de socio no puede estar en blanco.";
		die();
	}
	$user_id;

	$stmnt = sprintf("SELECT l.id FROM login l INNER JOIN profile p ON p.userid=l.id WHERE p.id=%d", $_POST['member_id']);
	
	$result = $user->sql_conn->query($stmnt);
	
	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		
		$user_id = $row['id'];
		
		$result->close();
	}
	
	if (empty($_POST['plate']))
	{
		echo "La tablilla del vehículo no puede estar en blanco.";
		die();
	}
	
	$stmnt = sprintf("SELECT * FROM vehicles WHERE userid=%d AND plate='%s'", $user_id, $_POST['plate']);
	
	$result = $user->sql_conn->query($stmnt);
	
	if ($result->num_rows == 0)
	{
		echo "Este vehículo no está registrado a este usuario.";
		die();
	}
	
	$result->close();

	$stmnt = sprintf("SELECT * FROM vehicles v, services s, plans p WHERE v.service_id=s.id AND s.plan_id=p.id AND s.userid=%d AND v.plate='%s'", $user_id, $_POST['plate']);
	
	$result = $user->sql_conn->query($stmnt);
	
	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();

		if ($row['occurrence_counter'] >= $row['num_occurrences'])
		{
			echo "La cantidad de ocurencias para este plan de servicio ha llegado a su máximo.";
			die();
		}
		else if($row['miles_counter'] >= $row['num_miles'])
		{
			echo "La cantidad de millas para este plan de servicio ha llegado a su máximo.";
			die();
		}
		else if ($row['renewal'] == 1)
		{
			echo "El plan de servicio para este usuario ha expirado.";
			die();
		}
		$result->close();
	}
	
	$stmnt = sprintf("UPDATE vehicles, services SET occurrence_counter=occurrence_counter+1, miles_counter=miles_counter+%d WHERE vehicles.service_id=services.id AND services.userid=%d AND vehicles.plate='%s'", $_POST['miles'], $user_id, $_POST['plate']);
	$user->sql_conn->query($stmnt);

	$stmnt = sprintf("INSERT INTO assist(customer_id, provider_id, assist_desc, assist_area, assist_city, assist_date, dest_area, dest_city, estimated_miles) 
		VALUES(%d,%d,'%s','%s','%s',NOW(),'%s','%s',%d)",
		$user_id, $_POST['provider_id'], $_POST['desc'], $_POST['place'], $_POST['city'], $_POST['dest'], $_POST['city2'], $_POST['miles']);
		
	$user->sql_conn->query($stmnt);
	
	echo "success";
	die();
	
}
else
{
	http_response_code(400);
	die();
}
?>