<?php session_start(); ?>

<?php include "php/conexion.php";?>


<h3 class="muted">Nuevo movimiento</h3>

<?php if (isset($_GET["ERR"])){
	$error = $_GET["ERR"];
	?>
	<div class="alert alert-danger">
  <strong>Error!</strong> <?php echo $error ?> no puede ser vacío.
</div>
<?php }?>

			<div class='form-actions'>
		    	<form id="frmConfiguracion" class="form-inline" action="php/documento/INS.php" method="POST" enctype="multipart/form-data">
    				<div style="margin-left: 80px; padding-left: -10; padding: 10px;">

							<select class="span4" name="ORGE" title="Seleccione unidad emisora" >
								<option value="" selected disabled>-- Seleccione unidad emisora --</option>
								<?php
									// CARGO EL COMBO ORGANISMO EMISOR
									$id_usuario = $_SESSION['id_usuario'];
									$sql = "SELECT id_organismo FROM usuarios_organismos WHERE id_usuario='".$id_usuario."'";

									$result = mysql_query($sql,$conex);
									while ($dir = mysql_fetch_array($result)) {
										$id_org=$dir['id_organismo'];
										$sql2 = "SELECT nombre FROM organismos WHERE id='".$id_org."'";
										$result2 = mysql_query($sql2, $conex);
										$dir2=mysql_fetch_array($result2);
										$nombre_org=$dir2['nombre'];
											if($VROL == $id_org) {
												echo "<option value='".$id_org."' selected>".$nombre_org."</option>\n";
											} else {
												echo "<option value='".$id_org."'>".$nombre_org."</option>\n";
											}
									} // end while
								?>
							</select>

							<?php if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario' || $_SESSION['tipo']=='operador') { ?>
								<select class="span4" name="ORG" title="Seleccione unidad receptora" >
									<option value="" selected disabled>-- Seleccione unidad receptora --</option>
									<?php
										// CARGO EL COMBO ORGANISMO RECEPTOR
										$sql = "SELECT id, nombre FROM organismos";
										$result = mysql_query($sql,$conex);
										while ($dir = mysql_fetch_array($result)) {
											$id_org=$dir['id'];
											$nombre_org=$dir['nombre'];
												if($VROL == $id_org) {
													echo "<option value='".$id_org."' selected>".$nombre_org."</option>\n";
												} else {
													echo "<option value='".$id_org."'>".$nombre_org."</option>\n";
												}
										} // end while
									?>
								</select>
							<?php } ?>

							<br></br>

							<input class='span8' type='text' placeholder='Observación' name="OBS">
							<br></br>
							<a href='documento.php' class="btn">
								<i class='icon-ban-circle'></i>&nbsp;Cancelar </a>
		  				<button  type='submit' class='btn btn-primary'><i class='icon-cog icon-white'></i>&nbsp;Guardar</button>
						</div>
				</form>
  		</div>

	<?php	 mysql_close($conex); ?>
