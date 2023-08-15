<?php session_start();

function getFamiliaById($id) {
  include "php/conexion.php";
  $sql="SELECT id, id_familia, nombre, descripcion, codigo FROM familias WHERE id=$id";
  $res = mysql_query($sql,$conex);
  $f = mysql_fetch_array($res);
	mysql_close($conex);
  return $f;
}

function opcionesFamilia($id) {
	include "php/conexion.php";
	$id_usuario = $_SESSION['id_usuario'];
  $sql = "SELECT id, nombre FROM familias ORDER BY nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		if($id == $row[0]) {
			?><option value="<?php echo $row[0]; ?>" selected><?php echo $row[1] ?></option> <?php
		} else {
			?><option value="<?php echo $row[0]; ?>" ><?php echo $row[1] ?></option> <?php
		}
	} // End while
	mysql_close($conex);
}

if (!isset($_GET["ID"])) {
  exit();
} else {
  $ID = $_GET["ID"];
}

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {
  $familia = getFamiliaById($ID);
  ?>
  <?php if ($familia[1]==""){ ?>
    <h3 class="muted">Modificar familia</h3>
  <?php } else { ?>
    <h3 class="muted">Modificar subfamilia</h3>
    <?php $f = getFamiliaById($familia[1]); ?>
    <div class="alert alert-info">
      <strong><?php echo $f[2] ?></strong> <?php echo $f[3] ?>
    </div>
  <?php } ?>


    <?php if (isset($_GET["ERR"])){
      $error = $_GET["ERR"]; ?>
      <div class="alert alert-danger">
        <strong>Error!</strong> <?php echo $error ?>
      </div>
    <?php }?>

			<div class='form-actions'>
		    	<form id="frmConfiguracion" class="form-inline" action="php/familia/UPD.php" method="POST" enctype="multipart/form-data">
    				<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
              <input class="span1" type="text" placeholder="ID" name="ID" value="<?php echo $familia[0] ?>" style="display:none;">
							<!--<select class="span4" name="FAM" title="Seleccione padre" >
								<option value="" selected disabled>-- Seleccione familia padre--</option>
								<?php //opcionesFamilia($familia[1]); ?>
							</select>-->
              <input class='span2' type='text' placeholder='C&oacute;digo' name="COD" value="<?php echo $familia[4] ?>">
							</br></br>
              <input class='span6' type='text' placeholder='Nombre' name="NOM" value="<?php echo $familia[2] ?>">
		    			</br></br>
              <textarea rows="3" class='span6' placeholder='Descripción' name="DES" value="<?php echo $familia[3] ?>"><?php echo $familia[3] ?></textarea>
							<br></br>
              <a href='familia.php' class="btn">
		            <i class='icon-ban-circle'></i>&nbsp;Cancelar </a>
		  				<button  type='submit' class='btn btn-primary'><i class='icon-cog icon-white'></i>&nbsp;Guardar</button>
						</div>
				</form>
  		</div>
			<!--<div class="tablaEditor">
			</div>-->

	<?php	 mysql_close($conex); ?>

<?php } ?>
