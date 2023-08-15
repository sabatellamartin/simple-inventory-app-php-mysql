<?php session_start();

// CARGO OPCIONES FAMILIA
function opcionesFamilia($id) {
	include "php/conexion.php";
	$sql = "SELECT f.id,
								 f.nombre,
								 f.codigo
				  FROM familias AS f
					WHERE f.id_familia IS NULL
					ORDER BY f.codigo ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		if($id == $row[0]) {
			?><option value="<?php echo $row[0] ?>" selected><?php echo $row[2]." - ".$row[1] ?></option> <?php
		} else {
			?><option value="<?php echo $row[0] ?>" ><?php echo $row[2]." - ".$row[1] ?></option> <?php
		}
	}
	mysql_close($conex);
}

// CARGO OPCIONES SUBFAMILIA
function opcionesSubfamilia($id) {
	include "php/conexion.php";
	$sql = "SELECT f.id,
								 f.nombre,
								 f.codigo
				  FROM familias AS f
					WHERE f.id_familia=$id
					ORDER BY f.codigo ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		?><option value="<?php echo $row[0] ?>" ><?php echo $row[2]." - ".$row[1] ?></option> <?php
	}
	mysql_close($conex);
}

function getFamiliaById($id) {
  include "php/conexion.php";
  $sql="SELECT id, id_familia, nombre, descripcion, codigo FROM familias WHERE id=$id";
  $res = mysql_query($sql,$conex);
  $f = mysql_fetch_array($res);
	mysql_close($conex);
  return $f;
}

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>

<?php include "php/conexion.php"; ?>

		<h3 class="muted">Nuevo artículo</h3>

		<?php if (isset($_GET["ERR"])){
			$error = $_GET["ERR"]; ?>
			<div class="alert alert-danger">
		  	<strong>Error!</strong> <?php echo $error ?>
			</div>
		<?php }?>


		<?php if (isset($_GET["OPT"])){
			$f = getFamiliaById($_GET["OPT"]); ?>
			<div class="alert alert-info">
		  	<strong><?php echo $f[2] ?></strong> <?php echo $f[3] ?>
			</div>
		<?php }?>

			<div class='form-actions'>
		    	<form id="frmConfiguracion" class="form-inline" action="php/articulo/INS.php" method="POST" enctype="multipart/form-data">
    				<div style="margin-left: 80px; padding-left: -10; padding: 10px;">

							<select class="span6" name="FAM" title="Seleccione familia" onchange="window.location.href='articulo.php?step=1&OPT='+this.value" >
								<option value="" selected disabled>-- Seleccione familia --</option>
								<?php opcionesFamilia($f[0]); ?>
							</select>
							<br></br>
							<select class="span6" name="SUB" title="Seleccione subfamilia">
								<option value="" selected disabled>-- Seleccione subfamilia --</option>
								<?php opcionesSubfamilia($f[0]); ?>
							</select>
							<br></br>
							<input class='span6' type='text' placeholder='Artículo' maxlength="1000" name="ART" required>
							<br></br>
							<textarea rows="4" class='span6' placeholder='Descripción' maxlength="10000" name="DES"></textarea>
							<br></br>

							<a href='articulo.php' class="btn">
		            <i class='icon-ban-circle'></i>&nbsp;Cancelar </a>
		  				<button  type='submit' class='btn btn-primary'><i class='icon-cog icon-white'></i>&nbsp;Guardar</button>
						</div>
				</form>
  		</div>
			<div class="tablaEditor">
			</div>

<?php } ?>
