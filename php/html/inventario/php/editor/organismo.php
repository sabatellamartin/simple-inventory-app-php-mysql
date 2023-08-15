<?php	 include "php/conexion.php";

$orden="id";
if (isset($_GET["ORD"])) {
	$orden = $_GET["ORD"];
}

if (isset($_GET["UPD"])) {

	$id_organismo = $_GET["UPD"];

	$sql="SELECT id, nombre,sigla, id_organismo FROM organismos WHERE id=$id_organismo";

   $res = mysql_query($sql,$conex);

   if(mysql_num_rows($res) != 0) {

   	while ($org = mysql_fetch_array($res)) {
			$id = $org['id'];
			$nombre = $org['nombre'];
			$sigla = $org['sigla'];
			$id_organismo = $org['id_organismo'];
		}// fin del while
	}// fin del if
?>

    <div class="form-actions">
   	<form class="form-horizontal" id="frmOrganismoUPD" action="php/editor/organismoUPD.php" method="POST" enctype="multipart/form-data">

    		<input class="span1" name="ID" style="display:none;" type="text" placeholder="ID" value="<?php echo $id ?>">

				<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-asterisk'></i></span>
    			<input class="span4" name="ORG" type="text" placeholder="Nombre"  value="<?php echo $nombre ?>">
    		</div>
				<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-asterisk'></i></span>
    			<input class="span4" name="SIG" type="text" placeholder="Sigla" value="<?php echo $sigla ?>">
    		</div>
				<br></br>
				<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-asterisk'></i></span>
					<select class="span4" name="PADRE" title="Modificar padre">
						<option value="" disabled selected>Seleccione padre</option>
						<?php
							// CARGO EL COMBO PADRE
							$sql = "SELECT id, nombre FROM organismos WHERE (fecha_baja IS NULL) AND (id <> $id)  AND (id_organismo <> $id) ORDER BY nombre ASC";
							$result = mysql_query($sql,$conex);
							while ($dir = mysql_fetch_array($result)) {
								$id_org=$dir['id'];
								$nombre_org=$dir['nombre'];
								if($id_organismo == $id_org) {
									echo "<option value='".$id_org."' selected>".$nombre_org."</option>\n";
								} else {
									echo "<option value='".$id_org."'>".$nombre_org."</option>\n";
								}
							} // end while
						?>
					</select>
    		</div>
      	<button type="submit" class="btn btn-primary">Modificar</button>
    		<button type="reset" onclick="window.location.href='editor.php?step=1'" class="btn">Cancelar</button>
		</form>
    </div>

<?php
} elseif(isset($_GET["DEL"])) {

	$id_organismo = $_GET["DEL"];

	$sql="SELECT id, nombre FROM organismos WHERE id=$id_organismo";

   $res = mysql_query($sql,$conex);

   if(mysql_num_rows($res) != 0) {

   	while ($dir = mysql_fetch_array($res)) {
			$id = $dir['id'];
			$nombre = $dir['nombre'];
		}// fin del while
	}// fin del if
?>

	<!-- Modal -->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 id="myModalLabel">Baja de unidad</h4>
		</div>
		<div class="modal-body">
			<p>¿Esta seguro de dar de baja la unidad <?php echo $nombre; ?>?</p>
		</div>
		<div class="modal-footer">
			<a href='editor.php?step=1' class="btn" aria-hidden="true">Cancelar</a>
			<a href='php/editor/organismoDEL.php?DEL=<?php echo $id; ?>' class="btn btn-primary">Baja</a>
		</div>
	</div>

<?php
} elseif(isset($_GET["HJ"])) {

	$id_organismo = $_GET["HJ"];

	$sql="SELECT id, nombre, sigla FROM organismos WHERE (id_organismo=$id_organismo) AND (fecha_baja IS NULL)";

   $res = mysql_query($sql,$conex);

   if(mysql_num_rows($res) != 0) {

?>

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" onclick="window.location.href='editor.php?step=1'" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Subunidades</h4>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-hover">
						<thead>
			        <tr>
			            <th><a href='editor.php?step=1&ORD=id'>ID</a></th>
									<th><a href='editor.php?step=1&ORD=sigla'>Sigla</a></th>
			            <th><a href='editor.php?step=1&ORD=nombre'>Unidad</th>
									<th></th>
			        </tr>
			      </thead>
						<tbody>
							<?php while ($dir = mysql_fetch_array($res)) { ?>
							<tr>
							  <td style="color:#6E6E6E"><?php echo $dir['id'] ?></td>
							  <td style="color:#6E6E6E"><?php echo $dir['nombre'] ?></td>
								<td style="color:#6E6E6E"><?php echo $dir['sigla'] ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php
	}// fin del if
	else { ?>
		<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" onclick="window.location.href='editor.php?step=1'" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Subunidades</h4>
				</div>
				<div class="modal-body">
					<p>La unidad <?php echo $nombre; ?> no tiene subunidades.</p>
				</div>
			</div>
		</div>
<?php
	}
} else {
?>


<?php if (isset($_GET["ERR"])){
	$error = $_GET["ERR"]; ?>
	<div class="alert alert-warning">
		<strong>Error!</strong> <?php echo $error ?>
	</div>
<?php }?>

   <div class="form-actions">
   	<form class="form-horizontal" id="frmOrganismo" action="php/editor/organismoINS.php" method="POST" enctype="multipart/form-data">
			<div class="row" style="margin-top:5px;margin-left:10px;">
				<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-map-marker'></i></span>
    			<input class="span4" name="ORG" type="text" placeholder="Nombre">
    		</div>
				<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-map-marker'></i></span>
    			<input class="span4" name="SIG" type="text" placeholder="Sigla">
    		</div>
				<br></br>
				<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-map-marker'></i></span>
					<select class="span4" name="PADRE" title="Seleccione padre">
						<option value="" disabled selected>Seleccione padre</option>
						<?php
							// CARGO EL COMBO PADRE
							$sql = "SELECT id, nombre, fecha_baja FROM organismos ORDER BY nombre ASC";
							$result = mysql_query($sql,$conex);
							while ($dir = mysql_fetch_array($result)) {
								$id_org=$dir['id'];
								$nombre_org=$dir['nombre'];
								$baja=$dir['fecha_baja'];
								if ($baja=="") {
									if($VROL == $id_org) {
										echo "<option value='".$id_org."' selected>".$nombre_org."</option>\n";
									} else {
										echo "<option value='".$id_org."'>".$nombre_org."</option>\n";
									}
								}
							} // end while
						?>
					</select>
    		</div>
      	<button type="submit" class="btn btn-primary">Crear unidad</button>
    		<button type="reset" class="btn">Cancelar</button>
			</div>
		</form>
  </div>


<?php
} // Fin del if
?>
	<div>
		<table class="table table-striped table-hover">
			<thead>
        <tr>
            <th><a href='editor.php?step=1&ORD=id'>ID</a></th>
						<th><a href='editor.php?step=1&ORD=sigla'>Sigla</a></th>
            <th><a href='editor.php?step=1&ORD=nombre'>Unidad</th>
						<th></th>
        </tr>
      </thead>
		<tbody>

<?php

$sql="SELECT id, nombre, sigla FROM organismos WHERE fecha_baja IS NULL ORDER BY $orden ASC";

$result = mysql_query($sql,$conex);

if(mysql_num_rows($result) != 0) {
	while ($reg = mysql_fetch_array($result)) {
		$id=$reg['id'];
  	$nombre=$reg['nombre'];
		$sigla=$reg['sigla'];
?>
	<tr>
	  <td><?php echo $id; ?></td>
		<td><?php echo $sigla; ?></td>
	  <td><?php echo $nombre; ?></td>
		<td style="text-align:right">
			<a href='editor.php?step=1&HJ=<?php echo $id; ?>' class='btn btn-primary' title="Ver hijos" type='submit'>
	   		<i class='icon-th icon-white'></i>
      </a>
	   	<a href='editor.php?step=1&UPD=<?php echo $id; ?>' class='btn btn-primary' title="Modificar" type='submit'>
	   		<i class='icon-refresh icon-white'></i>
      </a>
      <a href='editor.php?step=1&DEL=<?php echo $id; ?>' class='btn btn-danger' title="Baja de unidad" type='submit'>
    	 	<i class='icon-remove icon-white'></i>
   		</a>
	  </td>
	 </tr>

<?php
	} // fin del while
} // fin del if

$sql="SELECT id, nombre, sigla, fecha_baja FROM organismos WHERE fecha_baja IS NOT NULL ORDER BY $orden ASC";
$result = mysql_query($sql,$conex);



?>
		</tbody>
	</table>
</div>

<br></br>
<br></br>
<br></br>
<?php
	if(mysql_num_rows($result) != 0) {
 ?>
<font size="4" color=#585858>Unidades eliminadas..</font>
<br></br>

	<div class="tablaEditor">
		<table class="table table-striped table-hover">
			<thead>
        <tr>
            <th><a href='editor.php?step=1&ORD=id'>ID</a></th>
						<th><a href='editor.php?step=1&ORD=sigla'>Sigla</a></th>
            <th><a href='editor.php?step=1&ORD=nombre'>Unidad</th>
						<th><a href='editor.php?step=1&ORD=fecha_baja'>Fecha de baja</th>
						<th></th>
        </tr>
      </thead>
		<tbody>
<?php
	while ($reg = mysql_fetch_array($result)) {
		$id=$reg['id'];
  	$nombre=$reg['nombre'];
		$sigla=$reg['sigla'];
		$baja=$reg['fecha_baja'];
		?>
			<tr>
			  <td style="color:#6E6E6E"><?php echo $id; ?></td>
				<td style="color:#6E6E6E"><?php echo $sigla; ?></td>
			  <td style="color:#6E6E6E"><?php echo $nombre; ?></td>
				<td style="color:#6E6E6E"><?php echo $baja; ?></td>
			 </tr>

<?php
		} // fin del while
	} // fin del if
	else { ?>
		<div class="tablaEditor">
		</div>
<?php	}
mysql_close($conex);
?>
			</tbody>
		</table>
	</div>
