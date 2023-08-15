<?php session_start();
if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>

<?php include "php/conexion.php"; ?>

		<h3 class="muted">Nuevo adjudicatario</h3>

		<?php if (isset($_GET["ERR"])){
			$error = $_GET["ERR"]; ?>
			<div class="alert alert-danger">
			  <strong>Error!</strong> <?php echo $error ?>
			</div>
		<?php }?>

			<div class='form-actions'>
		    	<form id="frmConfiguracion" class="form-inline" action="php/adjudicatario/INS.php" method="POST" enctype="multipart/form-data">
    				<div style="margin-left: 80px; padding-left: -10; padding: 10px;">

							<input class='span4' type='text' placeholder='Nombre' name="NOM">
							<input class='span4' type='text' placeholder='Descripción' name="DES">
							</br></br>
							<input class='span4' type='text' placeholder='RUT' name="RUT">
							<input class='span4' type='text' placeholder='Contacto' name="CON">
							</br></br>
							<input class='span4' type='text' placeholder='Telefono' name="TEL">
							<input class='span4' type='text' placeholder='Email' name="EMA">
							</br></br>
							<a href='adjudicatario.php' class="btn">
		            <i class='icon-ban-circle'></i>&nbsp;Cancelar </a>
		  				<button  type='submit' class='btn btn-primary'><i class='icon-cog icon-white'></i>&nbsp;Guardar</button>
						</div>
				</form>
  		</div>
			<div class="tablaEditor">
			</div>

	<?php	 mysql_close($conex); ?>

<?php } ?>
