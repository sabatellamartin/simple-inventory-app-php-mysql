<?php

	include "php/conexion.php";

	// OBTENGO DATOS
	$usuario = htmlentities($_POST['USU'], ENT_QUOTES);
	// Pasar a sha en un futuro
	$password = htmlentities(md5($_POST['PAS']), ENT_QUOTES);
	//$password = htmlentities($_POST['PAS'], ENT_QUOTES);

	$sql = "SELECT u.id,u.usuario,u.clave,u.email,u.fecha_baja,r.descripcion
					FROM usuarios AS u
					INNER JOIN roles r ON u.id_rol = r.id
					WHERE u.usuario='".$usuario."'";

	// EJECUTAR SENTENCIA SQL
	$result = mysql_query($sql,$conex);

	// SI EL NUMERO DE FILAS DE LA CONSULTA ES DISTINTO DE 0, ENTONCES EXISTE.
	if(mysql_num_rows($result) != 0) {

  	// AHORA QUE SABEMOS QUE EXISTE, OBTENEMOS LOS DATOS.
  	while($row = mysql_fetch_array($result)) {
			// PASAMOS LAS VARIABLES POR COMODIDAD
			$id_usu		= $row["id"];
			$usu			= $row["usuario"];
			$pass			= $row["clave"];
			$email		= $row["email"];
			$tipo			= $row["descripcion"];
			$baja			= $row["fecha_baja"];
		} // FIN DEL WHILE
		// COMPROBAMOS SI NO ESTA DE BAJA.
   	if($baja == "") {
   		// COMPROBAMOS LA CONTRASEÑA.
   		if($password == $pass) {
      			$_SESSION['id_usuario'] = $id_usu;
      			$_SESSION['sesion_usuario'] = md5($usu . $pass);
      			$_SESSION['tipo'] = $tipo;
      			$_SESSION['usuario'] = $usu;
					// Audito inicio	correcto
					/*
					$fecha = date("Y-m-d H:i:s");
					$sql = "INSERT INTO auditorias (funcionalidad, observacion, log_usuario, log_insert) VALUES ('Inicio de sesión',  'Inicio exitoso: $usuario', '$id_usu', '$fecha')";
					// EJECUTAR SENTENCIA SQL
					mysql_query($sql,$conex);
					*/
					// REDIRECCIONO AL INICIO DE LA APLICACION
					header('Location: main.php');

      	} else {?>
				<div class='alert alert-info'>
 					<button type='button' class='close' data-dismiss='alert'>&times;</button>
					<h4>Intente nuevamente.</h4>Usuario o contraseña incorrecta.
				</div>
				<?php
					// Audito inicio contraseña incorrecta
					$fecha = date("Y-m-d H:i:s");
					$sql = "INSERT INTO auditorias (funcionalidad, observacion, log_usuario, log_insert) VALUES ('Inicio de sesión', 'Error de clave con usuario: $usuario', $id_usu, '$fecha')";
					// EJECUTAR SENTENCIA SQL
					mysql_query($sql,$conex);
    		} // FIN DEL IF PASS
			} else { ?>
				<div class='alert alert-info'>
					<button type='button' class='close' data-dismiss='alert'>&times;</button>
					<h4>Intente nuevamente.</h4>Usuario o contraseña incorrecta.
				</div>
				<?php
				// Audito usurio de baja intentando entrar
				$fecha = date("Y-m-d H:i:s");
				$sql = "INSERT INTO auditorias (funcionalidad, observacion, log_usuario, log_insert) VALUES ('Inicio de sesión', 'Baja intentando entrar, usuario: $usuario', $id_usu, '$fecha')";
				// EJECUTAR SENTENCIA SQL
				mysql_query($sql,$conex);
			} // FIN DEL IF BAJA
	} else {?>
		<div class='alert alert-info'>
 			<button type='button' class='close' data-dismiss='alert'>&times;</button>
			<h4>Intente nuevamente.</h4>Usuario o contraseña incorrecta.
		</div>
		<?php
		// Audito inicio Usuario incorrecta
		$fecha = date("Y-m-d H:i:s");
		$sql = "INSERT INTO auditorias (funcionalidad, observacion, log_usuario, log_insert) VALUES ('Inicio de sesión', 'No existe usuario: $usuario', 0, '$fecha')";
		// EJECUTAR SENTENCIA SQL
		mysql_query($sql,$conex);
	} //FIN DEL IF USER

  // CERRAR CONEXION
  mysql_close($conex);
?>
