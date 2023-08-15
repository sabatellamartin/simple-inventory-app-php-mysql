<?php

$orden="o.id";
if (isset($_GET["ORD"])) {
	$orden = $_GET["ORD"];
}

function opcionesOrganismo($id) {
	include "php/conexion.php";
	$id_usuario = $_SESSION['id_usuario'];
	$sql = "SELECT o.id, o.nombre, o.sigla
					FROM usuarios_organismos uo
					INNER JOIN organismos o ON o.id = uo.id_organismo
					WHERE uo.id_usuario = $id_usuario";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		if($id == $row[0]) {
			?><option value="<?php echo $row[0]; ?>" selected><?php echo $row[1]." - ".$row[2]; ?></option> <?php
		} else {
			?><option value="<?php echo $row[0]; ?>" ><?php echo $row[1]." - ".$row[2]; ?></option> <?php
		}
	} // End while
	mysql_close($conex);
}


function getOficina($id) {
	include "php/conexion.php";
	$sql = "SELECT o.id, o.nombre, org.id, org.nombre, org.sigla
					FROM oficinas o
					INNER JOIN organismos org ON o.id_organismo=org.id
					WHERE o.id = $id
					ORDER BY o.nombre ASC";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
	return mysql_fetch_array($result);
}

function getOficinas($order) {
	include "php/conexion.php";
	$id_usuario = $_SESSION['id_usuario'];
	$sql = "SELECT o.id, o.nombre, org.id, org.nombre, org.sigla
					FROM oficinas o
					INNER JOIN organismos org ON o.id_organismo = org.id
					WHERE org.id IN (SELECT o.id
									FROM usuarios_organismos uo
									INNER JOIN organismos o ON o.id = uo.id_organismo
									WHERE uo.id_usuario = $id_usuario)
					ORDER BY $order ASC";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
	return $result;
}

?>

<div class="row">
	<div class="col-8">
		<h3 class="muted text-left" style="margin-left:20px;">Administraci&oacute;n de oficinas</h3>
	</div>
	<div class="col-4">
		<a class='btn' href="oficina.php?step=1" title="Atr&aacute;s" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Distribuir items</a>
	</div>
</div>

<?php

if (isset($_GET["UPD"])) {
	$oficina = getOficina($_GET["UPD"]); ?>

    <div class="form-actions">
   	<form class="form-horizontal" id="frmTipoUPD" action="php/oficina/oficinaUPD.php" method="POST" enctype="multipart/form-data">
      	<div id="input-edificio" class="input-prepend input-append">
    			<span class="add-on">ID</span>
    			<input class="span1" type="text" placeholder="ID" value="<?php echo $oficina[0] ?>" disabled>
    			<input class="span1" name="ID" style="display:none;" type="text" placeholder="ID" value="<?php echo $oficina[0] ?>">
    		</div>

				<div class="input-prepend input-append">
					<span class="add-on"><i class='icon-chevron-down'></i></span>
					<select class="span5" name="ORG" title="Seleccione unidad">
						<option value="" selected>-- Seleccione unidad --</option>
						<?php opcionesOrganismo($oficina[2]); ?>
					</select>
				</div>

      	<div id="input-edificio" style="margin-top:10px;" class="input-prepend input-append">
    			<span class="add-on"><i class='icon-chevron-down'></i></span>
    			<input class="span4" name="NOM" type="text" placeholder="Nombre" value="<?php echo $oficina[1]; ?>">
    		</div>
      	<button type="submit" style="margin-top:10px;margin-right:5px;" class="btn btn-primary">Modificar</button>
    		<a href='oficina.php?step=2' style="margin-top:10px;" class="btn">Cancelar</a>
		</form>
    </div>


<?php
} elseif(isset($_GET["DEL"])) {
	$oficina = getOficina($_GET["DEL"]); ?>

	<!-- Modal -->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
			<h3 id="myModalLabel">Eliminar oficina</h3>
		</div>
		<div class="modal-body">
			<p>¿Esta seguro de eliminar la oficina <?php echo $oficina[1] ?>?</p>
		</div>
		<div class="modal-footer">
			<a href='oficina.php?step=7' class="btn" aria-hidden="true">Cancelar</a>
			<a href='php/oficina/oficinaDEL.php?DEL=<?php echo $oficina[0] ?>' class="btn btn-primary">Eliminar</a>
		</div>
	</div>

<?php
} else {
?>

<div class="alert alert-info">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Recuerde.</strong> Complete el formulario para agregar una nueva oficina en su unidad. Además puede modificar o dar de baja una oficina seleccionando la acción desde la lista.
</div>

   <div class="form-actions">
   	<form class="form-horizontal" id="frmTipo" action="php/oficina/oficinaINS.php" method="POST" enctype="multipart/form-data">

				<div  id="input-edificio" class="input-prepend input-append">
					<span class="add-on"><i class='icon-chevron-down'></i></span>
					<select class="span5" name="ORG" title="Seleccione unidad">
						<option value="" selected>-- Seleccione unidad de la oficina --</option>
						<?php opcionesOrganismo(""); ?>
					</select>
				</div>

      	<div id="input-edificio" style="margin-top:10px;" class="input-prepend input-append">
    			<span class="add-on"><i class='icon-tag'></i></span>
    			<input class="span3" name="NOM" type="text" placeholder="Nombre oficina">
    		</div>
      	<button type="submit" style="margin-top:10px;margin-right:5px;" class="btn btn-primary">Guardar</a>
    		<button type="reset" style="margin-top:10px;" class="btn">Limpiar</button>
		</form>
    </div>
<?php
} // Fin del if
?>
<div class="tablaEditor">
	<table class="table table-bordered table-striped table-hover">
	<thead>
    <tr>
    		<th></th>
        <th><a href='oficina.php?step=2&ORD=o.id'>ID</a></th>
				<th><a href='oficina.php?step=2&ORD=org.nombre'>Unidad</th>
        <th><a href='oficina.php?step=2&ORD=o.nombre'>Oficina</th>
    </tr>
  </thead>
  <tbody>

<?php
$result = getOficinas($orden);
if(mysql_num_rows($result) != 0) {
	while($oficina = mysql_fetch_array($result)) {
		$id=$reg['id'];
  	$motivo=$reg['motivo'];

?>
 	<tr>
	 <td>
	 	<a href='oficina.php?step=2&UPD=<?php echo $oficina[0] ?>' class='btn btn-primary' type='submit'>
	 		<i class='icon-refresh icon-white'></i>
	    </a>
	    <a href='oficina.php?step=2&DEL=<?php echo $oficina[0] ?>' class='btn btn-danger' type='submit'>
	    	 	<i class='icon-trash icon-white'></i>
	 		</a>
	 </td>
	 <td><?php echo $oficina[0]?></td>
	 <td><?php echo $oficina[3] ?></td>
	 <td><?php echo $oficina[1] ?></td>
	 </tr>
<?php
	} // fin del while
} // fin del if
?>
		</tbody>
	</table>
</div>
