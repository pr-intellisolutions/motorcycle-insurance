$(document).ready(function() {
	$("#save_config").click(function() {
		$.ajax({
			type: "POST",
			url: "save_config.php",
			data: {'siteName': $('#siteName').val(),
				   'siteDesc': $('#siteDesc').val(),
				   'siteHost': $('#siteHost').val(),
				   'siteModule': $('#siteModule').val()},
			success: function(data) {
				if (data === "success")
				{
					$('#modal').modal('show');
					$("#ModalLabel").html('<strong>Información</strong>');
					$("#ModalBody").html('¡La configuración del sitio ha sido guardada exitosamente!');
				}
				else
				{
					$('#modal').modal('show');
					$("#ModalLabel").html('<strong>Error</strong>');
					$("#ModalBody").html(data);
				}
			}
		});
	});
	$("#save_db").click(function() {
		$.ajax({
			type: "POST",
			url: "save_db.php",
			data: {'dbHost': $('#dbHost').val(),
				   'dbPort': $('#dbPort').val(),
				   'dbName': $('#dbName').val(),
				   'dbUser': $('#dbUser').val(),
				   'dbPass': $('#dbPass').val()},
			success: function(data) {
				if (data === "success")
				{
					$('#modal').modal('show');
					$("#ModalLabel").html('<strong>Información</strong>');
					$("#ModalBody").html('¡La configuración de la base de datos ha sido guardada exitosamente!');
				}
				else
				{
					$('#modal').modal('show');
					$("#ModalLabel").html('<strong>Error</strong>');
					$("#ModalBody").html(data);
				}
			}
		});
	});
	$("#save_policy").click(function() {
		$.ajax({
			type: "POST",
			url: "save_policy.php",
			data: {'userComplexity': $('#userComplexity').val(),
				   'userMinLen': $('#userMinLen').val(),
				   'userMaxLen': $('#userMaxLen').val(),
				   'passComplexity': $('#passComplexity').val(),
				   'passMinLen': $('#passMinLen').val(),
				   'passMaxLen': $('#passMaxLen').val(),
				   'passExpiration': $('#passExpiration').val(),
				   'maxLoginAttempts': $('#maxLoginAttempts').val(),
				   'activationReq': $('#activationReq').val()},
			success: function(data) {
				if (data === "success")
				{
					$('#modal').modal('show');
					$("#ModalLabel").html('<strong>Información</strong>');
					$("#ModalBody").html('¡Las políticas de seguridad han sido guardada exitosamente!');
				}
				else
				{
					$('#modal').modal('show');
					$("#ModalLabel").html('<strong>Error</strong>');
					$("#ModalBody").html(data);
				}
			}
		});
	});
	$('#cancel').click(function() {
		window.location.href = 'index.php';
	});
	$('#modal').on('hidden.bs.modal', function() { 
		//location.reload();
		window.location.href = 'index.php';
	});
});
