<?php	 include "php/conexion.php";

$orden="id";
if (isset($_GET["ORD"])) {
	$orden = $_GET["ORD"];
}

if (isset($_GET["UPD"])) {

	$id_motivo = $_GET["UPD"];

	$sql="SELECT id, motivo FROM motivos_baja WHERE id=$id_motivo";

   $res = mysql_query($sql,$conex);

   if(mysql_num_rows($res) != 0) {

   	while ($row = mysql_fetch_array($res)) {
			$id = $row['id'];
			$motivo = $row['motivo'];
		}// fin del while
	}// fin del if
?>

    <div class="form-actions">
   	<form class="form-horizontal" id="frmTipoUPD" action="php/editor/motivos_bajaUPD.php" method="POST" enctype="multipart/form-data">
      	<div id="input-id_edificio" class="input-prepend input-append">
    			<span class="add-on">ID</span>
    			<input class="span1" type="text" placeholder="ID" value="<?php echo $id ?>" disabled>
    			<input class="span1" name="ID" style="display:none;" type="text" placeholder="ID" value="<?php echo $id ?>">
    		</div>
      	<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-star'></i></span>
    			<input class="span3" name="MOT" type="text" placeholder="Motivo" value="<?php echo $motivo; ?>">
    		</div>
      	<button type="submit" class="btn btn-primary">Modificar</button>
    		<a href='editor.php?step=7' class="btn">Cancelar</a>
		</form>
    </div>

<?php
} elseif(isset($_GET["DEL"])) {

	$id_motivo = $_GET["DEL"];

	$sql="SELECT id, motivo FROM motivos_baja WHERE id=$id_motivo";

   $res = mysql_query($sql,$conex);

   if(mysql_num_rows($res) != 0) {

   	while ($row = mysql_fetch_array($res)) {
			$id = $row['id'];
			$motivo = $row['motivo'];
		}// fin del while
	}// fin del if
?>

	<!-- Modal -->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
			<h3 id="myModalLabel">Eliminar motivo de baja</h3>
		</div>
		<div class="modal-body">
			<p>¿Esta seguro de eliminar el motivo de baja <?php echo $motivo; ?>?</p>
		</div>
		<div class="modal-footer">
			<a href='editor.php?step=7' class="btn" aria-hidden="true">Cancelar</a>
			<a href='php/editor/motivos_bajaDEL.php?DEL=<?php echo $id; ?>' class="btn btn-primary">Eliminar</a>
		</div>
	</div>

<?php
} else {
?>
   <div class="form-actions">
   	<form class="form-horizontal" id="frmTipo" action="php/editor/motivos_bajaINS.php" method="POST" enctype="multipart/form-data">
      	<div id="input-edificio" class="input-prepend input-append">
    			<span class="add-on"><i class='icon-star'></i></span>
    			<input class="span3" name="MOT" type="text" placeholder="Motivo">
    		</div>
      	<button type="submit" class="btn btn-primary">Aceptar</a>
    		<button type="reset" class="btn">Nuevo</button>
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
        <th><a href='editor.php?step=7&ORD=id'>ID</a></th>
        <th><a href='editor.php?step=7&ORD=descripcion'>Motivo de baja</th>
    </tr>
  </thead>
  <tbody>

<?php
$sql="SELECT id, motivo FROM motivos_baja ORDER BY $orden ASC";

$result = mysql_query($sql,$conex);

if(mysql_num_rows($result) != 0) {

	while ($reg = mysql_fetch_array($result)) {
		$id=$reg['id'];
  	$motivo=$reg['motivo'];

?>
 	<tr>
	 <td>
	 	<a href='editor.php?step=7&UPD=<?php echo $id; ?>' class='btn btn-primary' type='submit'>
	 		<i class='icon-refresh icon-white'></i>
	    </a>
	    <a href='editor.php?step=7&DEL=<?php echo $id; ?>' class='btn btn-danger' type='submit'>
	    	 	<i class='icon-trash icon-white'></i>
	 		</a>
	 </td>
	 <td><?php echo $id; ?></td>
	 <td><?php echo $motivo; ?></td>
	 </tr>
<?php
	} // fin del while
} // fin del if
mysql_close($conex);
?>
		</tbody>
	</table>
</div>
