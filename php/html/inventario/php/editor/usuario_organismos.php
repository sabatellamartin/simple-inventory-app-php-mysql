<?php session_start(); ?>
<?php include "php/conexion.php"; ?>

<?php
// SI SE PASA PARAMETRO POR GET Y TIENE ROL DE AMINISTRADOR
if (isset($_GET["USR"]) && $_SESSION['tipo'] == "administrador") {
	$id_usuario = $_GET["USR"];
	$sql = "SELECT id,nombre,apellido,usuario,email,fecha_baja
	 				FROM usuarios
	 				WHERE id='".$id_usuario."'";

	$result = mysql_query($sql,$conex);
	// SI EXISTE EL USUARIO
	if(mysql_num_rows($result) != 0) {
		while ($conf = mysql_fetch_array($result)) {
			$id_usuario =	$conf['id'];
			$nombre =	$conf['nombre'];
			$apellido	= $conf['apellido'];
			$usuario = $conf['usuario'];
			$email =	$conf['email'];
			$fecha_baja =	$conf['fecha_baja'];

			// SI EL USUARIO ES BAJA =>
			if ($fecha_baja != "") {
				?>
				<h4>Usuario de baja</h4>
				<p>Usuario <?php echo $nombre." ".$apellido; ?> no puede ser modificado desde <?php echo $fecha_baja; ?></p>
				<a href='editor.php?step=5' class='btn btn-info' type='submit'>
						<i class='icon-arrow-left icon-white'></i> Volver
				</a>
				<?php
				$referencia= $_SERVER['HTTP_REFERER'];
				header ("Location: ".$referencia."");
				exit();
			}
?>

<h4>Asignar pertenencia a unidades</h4>
<p>
	<b class="muted"><?php echo "Usuario: ".$usuario; ?></b><br>
	<b class="muted"><?php echo "Nombre: ".$nombre." ".$apellido; ?></b><br>
	<b class="muted"><?php echo "Email: ".$email; ?></b>
</p>

	<div style="width: 430px; float: left;">

	  	<form class='form-inline' action="php/editor/usuario_organismosINS.php" method="POST" enctype="multipart/form-data">
	  			<label class='control-label' style="padding:10px">Unidades</label>
	  			<select class='span3' name='IDORG'>
					<?php
						// CARGO EL COMBO
						$sql = "SELECT id, nombre, sigla FROM organismos ORDER BY nombre ASC";
						$result = mysql_query($sql,$conex);
						while ($row = mysql_fetch_array($result)) {
							echo "<option value='".$row['id']."'>".$row['nombre'].". (".$row['sigla'].")</option>\n";
						} // end while
					?>
				</select>
				<input class='input-medium' name="IDUSR" type='text' style="display:none;" value='<?php echo $id_usuario; ?>'>
				<button class='btn btn-primary' style="margin-bottom: 0px;" type='submit'><i class='icon-plus-sign icon-white'></i></button>
			</form>


		<table class='table table-striped table-bordered table-hover'>
			<thead>
				<tr>
					<th>ID</th>
					<th>Unidad</th>
					<th>Sigla</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sql = "SELECT uo.id,o.nombre,o.sigla
								FROM usuarios_organismos AS uo
								INNER JOIN organismos o ON uo.id_organismo = o.id
								INNER JOIN usuarios u ON uo.id_usuario = u.id
								WHERE u.id='".$id_usuario."'";

				$result = mysql_query($sql,$conex);
				if(mysql_num_rows($result) != 0) {
					while ($row = mysql_fetch_array($result)) {
						$id_uo = $row['id'];
						$o_nombre =	$row['nombre'];
						$o_sigla = $row['sigla'];
				?>
					<tr>
						<!--<td><a class='btn btn-mini btn-danger' href='php/info/empleadoDEL.php?DEL=<?php echo $id_uo; ?>' type='submit'><i class='icon-trash icon-white'></i></a></td>-->
						<td><?php echo $id_uo; ?></td>
						<td><?php echo $o_nombre; ?></td>
						<td><?php echo $o_sigla; ?></td>
					</tr>
				<?php
					} // Fin del if
				} // fin del while
				?>
		</tbody>
	</table>

</div>
<?php
		} // end while
	} // fin del if
	mysql_close($conex);
// SI NO SE PASA PARAMETRO USR POR GET O EL USUARIO NO ADMINISTRADOR =>
} else {
	// RETORNO
	$referencia= $_SERVER['HTTP_REFERER'];
	header ("Location: ".$referencia."");
}
?>
