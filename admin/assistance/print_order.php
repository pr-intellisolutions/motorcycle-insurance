<html>
<?php

require_once('../../common.php');

$stmnt = sprintf("select p.id as member_id, p.first, p.middle, p.last, p.address1, p.address2, p.city, p.state, p.zip, p.phone, l.email,
	v.brand, v.model, v.year, v.serial, v.plate, v.service_id, o.id as po, o.description, o.area, o.city, o.estimated_miles, o.order_date,
	(select CONCAT (first, ' ', middle, ' ', last) from providers inner join profile on providers.profile_id=profile.id where providers.id=o.provider_id) as fullName,
	(select companyName from providers inner join profile on providers.profile_id=profile.id where providers.id=o.provider_id) as companyName,
	(select companyPhone from providers inner join profile on providers.profile_id=profile.id where providers.id=o.provider_id) as companyPhone,
	(select companyEmail from providers inner join profile on providers.profile_id=profile.id where providers.id=o.provider_id) as companyEmail
	from orders o inner join vehicles v on o.vehicle_id=v.id inner join login l on o.customer_id=l.id inner join profile p on p.userid=l.id and o.id=%d", $_GET['id']);

$result = $user->sql_conn->query($stmnt);

if ($result->num_rows > 0)
{
	$row = $result->fetch_assoc();
}
?>
<img src=<?php echo SITE_URL.'styles/images/logo.png'?> alt="logo" style="width:304px;height:187px;">

<h1>Transporte Rubeli Ortiz <span style="float:right; vertical-align: bottom; font-size:12pt">PO: 0000<?php echo $row['po']?></span></h1>
<hr style="clear:both;"><hr>
<h3>Información del cliente</h3>
<p style="float:left; padding:5px;"><strong>Número de socio</strong><br><?php echo $row['member_id']?></p>
<p style="float:left; padding:5px;"><strong>Nombre del cliente</strong><br><?php echo $row['first'].' '.$row['middle'].' '.$row['last']?></p>
<p style="float:left; padding:5px;"><strong>Dirección Física</strong><br><?php echo $row['address1']?><br><?php echo $row['address2']?><br><?php echo $row['city'].', '.$row['state'].', '.$row['zip']?></p>
<p style="float:left; padding:5px;"><strong>Número de teléfono</strong><br><?php echo $row['phone']?></p>
<p style="float:left; padding:5px;"><strong>Correo electrónico</strong><br><?php echo $row['email']?></p>
<hr style="clear:both;">
<h3>Información del vehículo</h3>
<p style="float:left; padding:5px;"><strong>Marca</strong><br><?php echo $row['brand']?></p>
<p style="float:left; padding:5px;"><strong>Modelo</strong><br><?php echo $row['model']?></p>
<p style="float:left; padding:5px;"><strong>Año</strong><br><?php echo $row['year']?></p>
<p style="float:left; padding:5px;"><strong>Número de serie</strong><br><?php echo $row['serial']?></p>
<p style="float:left; padding:5px;"><strong>Tablilla</strong><br><?php echo $row['plate']?></p>
<p style="float:left; padding:5px;"><strong>Plan de servicio</strong><br><?php echo $row['service_id']?></p>
<hr style="clear:both;">
<h3>Información del servicio</h3>
<p style="float:left; padding:5px;"><strong>Descripción del incidente</strong><br><?php echo $row['description']?></p>
<p style="float:left; padding:5px;"><strong>Lugar donde ocurrió</strong><br><?php echo $row['area']?></p>
<p style="float:left; padding:5px;"><strong>Ciudad</strong><br><?php echo $row['city']?></p>
<p style="float:left; padding:5px;"><strong>Fecha</strong><br><?php echo $row['order_date']?></p>
<p style="float:left; padding:5px;"><strong>Millas estimadas</strong><br><?php echo $row['estimated_miles']?></p>
<hr style="clear:both;">
<h3>Información del proveedor de servicio</h3>
<p style="float:left; padding:5px;"><strong>Nombre del proveedor</strong><br><?php echo $row['fullName']?></p>
<p style="float:left; padding:5px;"><strong>Nombre de la compañía</strong><br><?php echo $row['companyName']?></p>
<p style="float:left; padding:5px;"><strong>Número de teléfono</strong><br><?php echo $row['companyPhone']?></p>
<p style="float:left; padding:5px;"><strong>Correro electrónico</strong><br><?php echo $row['companyEmail']?></p>
<hr style="clear:both;"><hr>
</html>