<?php session_start();

function opcionesEmisor($id_usuario) {
	include "php/conexion.php";
	$sql = "SELECT o.id,
								 o.nombre
	 				FROM usuarios_organismos AS uo
					LEFT JOIN organismos o ON o.id=uo.id_organismo
					WHERE uo.id_usuario='".$id_usuario."'";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		?><option value="<?php echo $row[0] ?>" ><?php echo $row[1] ?></option> <?php
	} // end while
	mysql_close($conex);
}

function opcionDepositoIngreso() {
	include "php/conexion.php";
	$sql = "SELECT o.id,
								 o.nombre,
								 o.sigla
	 				FROM organismos AS o
					WHERE o.sigla='D01'";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		?><option value="<?php echo $row[0] ?>" selected><?php echo $row[2]." - ".$row[1] ?></option> <?php
	} // end while
	mysql_close($conex);
}

?>

<?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>

<h3 class="muted">Nuevo documento de ingreso de items</h3>

<div class="alert alert-info">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Recuerde.</strong> Seleccione unidad de emisión del documento de ingreso, el destino de los items será en todos los casos el depósito D01.
	Los items se agregan después de la creación de este documento.
	Si lo desea puede especificar una observación general respecto al ingreso de items.
</div>

<?php if (isset($_GET["ERR"])){ ?>
	<div class="alert alert-warning">
  <strong>Atención!</strong> <?php echo $_GET["ERR"] ?>
</div>
<?php }?>

<div class='form-actions'>
  	<form id="frmConfiguracion" class="form-inline" action="php/ingreso/INS.php" method="POST" enctype="multipart/form-data">
			<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
				<!--Unidad emisora -->
				<select class="span4" name="ORGE" title="Seleccione unidad emisora" >
					<option value="" selected disabled>-- Seleccione unidad emisora --</option>
					<?php opcionesEmisor($_SESSION['id_usuario']); ?>
				</select>
				<!--Unidad receptora -->
				<select class="span4" name="ORGR" title="Seleccione unidad receptora" >
					<?php opcionDepositoIngreso(); ?>
				</select>
				<br></br>
				<input class='span8' type='text' placeholder='Observación' name="OBS">
				<br></br>
				<a href='ingreso.php' class="btn">
					<i class='icon-ban-circle'></i>&nbsp;Cancelar </a>
				<button  type='submit' class='btn btn-primary'><i class='icon-cog icon-white'></i>&nbsp;Crear documento de ingreso</button>
			</div>
	</form>
</div>

<?php } ?>
