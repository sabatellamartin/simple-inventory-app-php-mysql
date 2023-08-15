<?php session_start();
if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>

<?php if (isset($_GET["ID"])) {
        $id = $_GET["ID"];
      } else { ?>
        <div class="alert alert-danger">
      	  <strong>Error!</strong> parametros incorrectos.
      	</div>
<?php } ?>

<?php include "php/conexion.php";

$sql="SELECT id,rut,nombre,descripcion,contacto,telefono,email FROM adjudicatarios WHERE id=$id";

 $res = mysql_query($sql,$conex);

 if(mysql_num_rows($res) != 0) {

	while ($row = mysql_fetch_array($res)) {
		$id = $row['id'];
		$rut = $row['rut'];
		$nombre = $row['nombre'];
		$descripcion = $row['descripcion'];
		$contacto = $row['contacto'];
		$telefono = $row['telefono'];
		$email = $row['email'];
	} // fin del while
} // fin del if?
?>


		<h3 class="muted">Modificar adjudicatario</h3>
			<div class='form-actions'>
		    	<form id="frmConfiguracion" class="form-inline" action="php/adjudicatario/UPD.php" method="POST" enctype="multipart/form-data">
    				<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
							<input class="span1" type="text" placeholder="ID" name="ID" value="<?php echo $id ?>" style="display:none;">
              <input class='span4' type='text' placeholder='Nombre' name="NOM" value="<?php echo $nombre ?>" >
              <input class='span4' type='text' placeholder='Descripción' name="DES" value="<?php echo $descripcion ?>" >
              </br></br>
              <input class='span4' type='text' placeholder='RUT' name="RUT" value="<?php echo $rut ?>" >
              <input class='span4' type='text' placeholder='Contacto' name="CON" value="<?php echo $contacto ?>" >
              </br></br>
              <input class='span4' type='text' placeholder='Telefono' name="TEL" value="<?php echo $telefono ?>" >
              <input class='span4' type='text' placeholder='Email' name="EMA" value="<?php echo $email ?>" >
              </br></br>
              <a href='adjudicatario.php' class="btn">
		            <i class='icon-ban-circle'></i>&nbsp;Cancelar </a>
		  				<button  type='submit' class='btn btn-primary'><i class='icon-cog icon-white'></i>&nbsp;Guardar</button>
						</div>
				</form>
  		</div>
			<div class="tablaEditor">
			</div>

<?php } ?>
