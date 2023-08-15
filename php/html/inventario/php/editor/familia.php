<?php	 include "php/conexion.php";

$orden="id";
if (isset($_GET["ORD"])) {
	$orden = $_GET["ORD"];
}

if (isset($_GET["UPD"])) {

	$id_familia = $_GET["UPD"];

	$sql="SELECT id, nombre FROM familias WHERE id=$id_familia";

   $res = mysql_query($sql,$conex);

   if(mysql_num_rows($res) != 0) {

   	while ($fam = mysql_fetch_array($res)) {
			$id = $fam['id'];
			$nombre = $fam['nombre'];

		}// fin del while
	}// fin del if
?>

    <div class="form-actions">
   	<form class="form-horizontal" id="frmOficinaUPD" action="php/editor/familiaUPD.php" method="POST" enctype="multipart/form-data">
      	<div id="input-id_edificio" class="input-prepend input-append">
    			<span class="add-on">ID</span>
    			<input class="span1" type="text" placeholder="ID" value="<?php echo $id ?>" disabled>
    			<input class="span1" name="ID" style="display:none;" type="text" placeholder="ID" value="<?php echo $id ?>">
    		</div>
      	<div class="input-prepend input-append">

    			<input class="span3" name="FAM" type="text" placeholder="Nombre" value="<?php echo $nombre; ?>">
    		</div>
				<div class="input-prepend input-append">

    			<input class="span3" name="DES" type="text" placeholder="Modifica descripción" value="Descripción">
    		</div>
      	<button type="submit" class="btn btn-primary">Modificar</button>
    		<button type="reset" class="btn">Cancelar</button>
		</form>
    </div>

<?php
} elseif(isset($_GET["DEL"])) {

	$id_familia = $_GET["DEL"];

	$sql="SELECT id, nombre FROM familias WHERE id=$id_familia";

   $res = mysql_query($sql,$conex);

   if(mysql_num_rows($res) != 0) {

   	while ($fam = mysql_fetch_array($res)) {
			$id = $fam['id'];
			$nombre = $fam['nombre'];

		}// fin del while
	}// fin del if
?>

	<!-- Modal -->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h3 id="myModalLabel">Eliminar familia</h3>
		</div>
		<div class="modal-body">
			<p>¿Esta seguro de eliminar la familia <?php echo $nombre; ?>?. Eliminar la familia <?php echo $nombre; ?> puede producir inconsistencias en los datos relacionados a la misma.</p>
		</div>
		<div class="modal-footer">
			<a href='editor.php?step=2' class="btn" aria-hidden="true">Cancelar</a>
			<a href='php/editor/familiaDEL.php?DEL=<?php echo $id; ?>' class="btn btn-primary">Eliminar familia</a>
		</div>
	</div>

<?php
} else {
	if (isset($_GET["ERR"])){
		$error = $_GET["ERR"]; ?>
		<div class="alert alert-danger">
		<strong>Error!</strong> <?php echo $error ?> no puede ser vacío.
	</div>
	<?php }
?>
   <div class="form-actions">
   	<form class="form-horizontal" id="frmOficina" action="php/editor/familiaINS.php" method="POST" enctype="multipart/form-data">
				<div class="row" style="margin-top:5px;margin-left:10px;">
				<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-map-marker'></i></span>
    			<input class="span4" name="FAM" type="text" placeholder="Nombre">
    		</div>
				<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-map-marker'></i></span>
    			<input class="span4" name="DES" type="text" placeholder="Descripción">
    		</div>
				<br></br>
				<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-map-marker'></i></span>
					<select class="span4" name="PADRE" title="Seleccione padre">
						<option value="" selected>Seleccione padre</option>
						<?php
							// CARGO EL COMBO PADRE
							$sql = "SELECT id, nombre FROM familias ORDER BY nombre ASC";
							$result = mysql_query($sql,$conex);
							while ($dir = mysql_fetch_array($result)) {
								$id_fam=$dir['id'];
								$nombre_fam=$dir['nombre'];
									if($VROL == $id_fam) {
										echo "<option value='".$id_fam."' selected>".$nombre_fam."</option>\n";
									} else {
										echo "<option value='".$id_fam."'>".$nombre_fam."</option>\n";
									}
							} // end while
						?>
					</select>
    		</div>
      	<button type="submit" class="btn btn-primary">Crear familia</a>
    		<button type="reset" onclick="window.location.href='editor.php?step=2'" class="btn">Cancelar</button>
			</div>
		</form>
    </div>


<?php
} // Fin del if
?>
	<div class="tablaEditor">
			<table class="table table-striped table-hover">
				<thead>
              <tr>
                  <th><a href='editor.php?step=2&ORD=id'>ID</a></th>
                  <th><a href='editor.php?step=2&ORD=oficina'>Nombre</th>
									<th>Descripción</th>
									<th></th>
              </tr>
            </thead>

            <tbody>

<?php		$sql="SELECT id, nombre, descripcion FROM familias ORDER BY $orden ASC";

        	$result = mysql_query($sql,$conex);

        	if(mysql_num_rows($result) != 0) {

        		while ($reg = mysql_fetch_array($result)) {

			   	$id=$reg['id'];
			      $nombre=$reg['nombre'];
						$desc=$reg['descripcion'];

?>
			<tr>
				<td><?php echo $id; ?></td>
				<td><?php echo $nombre; ?></td>
				<td><?php echo $desc; ?></td>
				<td style="text-align:right">
			   	<a href='editor.php?step=2&UPD=<?php echo $id; ?>' class='btn btn-primary' type='submit'>
			   		<i class='icon-refresh icon-white'></i>
			      </a>
			      <a href='editor.php?step=2&DEL=<?php echo $id; ?>' class='btn btn-danger' type='submit'>
            	 	<i class='icon-trash icon-white'></i>
         		</a>
			   </td>
       	 </tr>
<?php
				} // fin del while
			} // fin del if

			mysql_close($conex);
?>
			</tbody>
		</table>
	</div>
