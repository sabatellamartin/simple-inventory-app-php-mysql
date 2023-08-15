<?php	 include "php/conexion.php";

$orden="id";
if (isset($_GET["ORD"])) {
	$orden = $_GET["ORD"];
}

if (isset($_GET["UPD"])) {

	$id_estado = $_GET["UPD"];

	$sql="SELECT id, nombre, descripcion FROM estados_documento WHERE id=$id_estado";

   $res = mysql_query($sql,$conex);

   if(mysql_num_rows($res) != 0) {

   	while ($row = mysql_fetch_array($res)) {
			$id = $row['id'];
			$nombre = $row['nombre'];
			$desc = $row['descripcion'];

		}// fin del while
	}// fin del if
?>

    <div class="form-actions">
   	<form class="form-horizontal" id="frmEstadoUPD" action="php/editor/estados_documentoUPD.php" method="POST" enctype="multipart/form-data">
      	<div class="input-prepend input-append">
    			<span class="add-on">ID</span>
    			<input class="span1" type="text" placeholder="ID" value="<?php echo $id ?>" disabled>
    			<input class="span1" name="ID" style="display:none;" type="text" placeholder="ID" value="<?php echo $id ?>">
    		</div>
      	<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-home'></i></span>
    			<input class="span3" name="NOM" type="text" placeholder="Nombre" value="<?php echo $nombre; ?>">
    		</div>
				<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-home'></i></span>
    			<input class="span3" name="DES" type="text" placeholder="Descripción" value="<?php echo $desc; ?>">
    		</div>
      	<button type="submit" class="btn btn-primary">Modificar</button>
    		<button type="reset" onclick="window.location.href='editor.php?'" class="btn">Cancelar</button>
		</form>
    </div>

<?php
} elseif(isset($_GET["DEL"])) {

	$id_estado = $_GET["DEL"];

	$sql="SELECT id, nombre FROM estados_documento WHERE id=$id_estado";

   $res = mysql_query($sql,$conex);

   if(mysql_num_rows($res) != 0) {

   	while ($row = mysql_fetch_array($res)) {
			$id = $row['id'];
			$nombre = $row['nombre'];

		}// fin del while
	}// fin del if
?>

	<!-- Modal -->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h3 id="myModalLabel">Eliminar Estado de documento</h3>
		</div>
		<div class="modal-body">
			<p>¿Esta seguro de eliminar el estado de documento <?php echo $nombre; ?>?</p>
		</div>
		<div class="modal-footer">
			<a href='editor.php' class="btn" aria-hidden="true">Cancelar</a>
			<a href='php/editor/estados_documentoDEL.php?DEL=<?php echo $id; ?>' class="btn btn-primary">Eliminar</a>
		</div>
	</div>

<?php
} else {
?>
   <div class="form-actions">
   	<form class="form-horizontal" id="frmEdificio" action="php/editor/estados_documentoINS.php" method="POST" enctype="multipart/form-data">
      	<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-home'></i></span>
    			<input class="span3" name="NOM" type="text" placeholder="Nombre">
    		</div>
				<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-home'></i></span>
    			<input class="span3" name="DES" type="text" placeholder="Descripción">
    		</div>
      	<button type="submit" class="btn btn-primary">Crear</a>
    		<button type="reset" class="btn">Cancelar</button>
		</form>
    </div>

<?php
} // Fin del if
?>
	<div class="tablaEditor">
			<table class="table table-striped table-hover">
				<thead>
              <tr>
                  <th><a href='editor.php?ORD=id'>ID</a></th>
                  <th><a href='editor.php?ORD=nombre'>Nombre</th>
									<th>Descripción</th>
									<th></th>
              </tr>
            </thead>

            <tbody>

<?php		$sql="SELECT id, nombre, descripcion FROM estados_documento ORDER BY $orden ASC";

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
 			   	<a href='editor.php?UPD=<?php echo $id; ?>' class='btn btn-primary' type='submit'>
 			   		<i class='icon-refresh icon-white'></i>
 			      </a>
 			      <a href='editor.php?DEL=<?php echo $id; ?>' class='btn btn-danger' type='submit'>
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
