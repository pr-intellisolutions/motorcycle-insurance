<?php

require_once('../../common.php');
require_once('../../includes/fpdf.php');

$template->set_custom_template(DIR_BASE.'styles', 'default');

if ($user->auth() && $user->role === 'admin')
{
	$template->assign_vars(array('SITE_URL' => SITE_URL,
		'SIDE_CONTENT' => 'home',
		'FORM_ACTION' => $_SERVER['PHP_SELF'],
		'FORM_METHOD' => 'POST',
		'USERNAME' => $user->user));

	if (isset($_POST['action']) && $_POST['action'] === 'generate_report')
	{
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);
		$pdf->Image(SITE_URL.'styles\images\logo.png',10,6,30);
		$pdf->Cell(40,45,'Transporte Rubeli Ortiz Reporte de Finanzas');
		$pdf->Text(20, 45, '---------- Fin del Reporte ----------');
		$pdf->Output();
	}
	else
	{
		$stmnt = sprintf("SELECT amount FROM sales");
		
		$result = $user->sql_conn->query($stmnt);
		
		if ($result->num_rows > 0)
		{
			$total_sales = 0;
			$total_amt = 0;
			while ($row = $result->fetch_assoc())
			{
				$total_sales++;
				$total_amt += $row['amount'];
			}
			$template->assign_vars(array('TOTAL_SALES' => $total_sales,
				'TOTAL_AMT' => sprintf("$%.2f",$total_amt)));
			$result->close();
		}
	}
	$template->set_filenames(array('body' => 'admin_finances.html'));
	$template->display('body');
}
else
{
	header('Location: '.SITE_URL);
	die();
}
?>
