<?php

function messageDelete($id) {
	include "php/conexion.php";
	$sql="SELECT id, nombre FROM adjudicatarios WHERE id=$id";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		$id = $row['id'];
		$nombre = $row['nombre'];
	}// fin del while
	mysql_close($conex);
  ?>
  	<!-- Modal -->
  	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  		<div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  			<h3 id="myModalLabel">Eliminar adjudicatario</h3>
  		</div>
  		<div class="modal-body">
  			<p>¿Esta seguro de eliminar el adjudicatario <?php echo $nombre; ?>?</p>
  		</div>
  		<div class="modal-footer">
  			<a href='adjudicatario.php' class="btn" aria-hidden="true">Cancelar</a>
  			<a href='php/adjudicatario/DEL.php?DEL=<?php echo $id; ?>' class="btn btn-primary">Eliminar</a>
  		</div>
  	</div>
  <?php
}


include "php/conexion.php";

if (isset($_GET["ORD"])) {
   $orden = $_GET["ORD"];
} else {
 $orden = "a.id";
}

if (isset($_GET["NOM"])) {
	$nombre = $_GET["NOM"];
} else {
	$nombre = "";
}
if (isset($_GET["RUT"])) {
	$rut = $_GET["RUT"];
} else {
	$rut = "";
}
?>
<div class="row">
	<div class="col-8">
		<h3 class="muted text-left" style="margin-left:20px;">Adjudicatarios</h3>
	</div>
	<div class="col-4">
		<?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
    <a class='btn' href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Atr&aacute;s" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Atr&aacute;s</a>
		<a class='btn btn-primary' href='adjudicatario.php?step=1' title="Agregar adjudicatario" style="margin-top:-40px;margin-bottom:10px;float:right;margin-right:90px;"><i class='icon-plus-sign icon-white'></i>&nbsp;&nbsp;Agregar</a>
		<?php } ?>
	</div>
</div>


<?php // Delete message
if(isset($_GET["DEL"])) {
  messageDelete($_GET["DEL"]);
} ?>

<!-- Jumbotron -->
<div class="form-actions">
  <form id="form" class="form-inline" action="adjudicatario.php" method="GET">
		<fieldset id="frm">
		<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
    <input name='RUT' class='span2' type='text' value='<?php echo $rut; ?>' placeholder='RUT'>
  	<input name='NOM' class='span4' type='text' value='<?php echo $nombre; ?>' placeholder='Nombre'>
 		<button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i>&nbsp;&nbsp;Buscar</button>
    <a class="btn" type="button" href="adjudicatario.php" onclick="resetBuscar()" value="Limpiar"><i class="icon-refresh"></i>&nbsp;&nbsp;Limpiar</a>
	</div>
 	</fieldset>
	</form>
</div>

<hr>

<div class="tablaEditor">
	<table class="table table-striped table-hover">
  	<thead>
	  	<tr>
        <th><a href='adjudicatario.php?ORD=a.rut&NOM=<?php echo $nombre; ?>&RUT=<?php echo $rut; ?>'>RUT</a></th>
        <th><a href='adjudicatario.php?ORD=a.nombre&NOM=<?php echo $nombre; ?>&RUT=<?php echo $rut; ?>'>Nombre</a></th>
        <th><a href='adjudicatario.php?ORD=a.descripcion&NOM=<?php echo $nombre; ?>&RUT=<?php echo $rut; ?>'>Descripci&oacute;n</a></th>
        <th><a href='adjudicatario.php?ORD=a.email&NOM=<?php echo $nombre; ?>&RUT=<?php echo $rut; ?>'>Email</a></th>
        <th><a href='adjudicatario.php?ORD=a.contacto&NOM=<?php echo $nombre; ?>&RUT=<?php echo $rut; ?>'>Contacto</a></th>
        <th><a href='adjudicatario.php?ORD=a.telefono&NOM=<?php echo $nombre; ?>&RUT=<?php echo $rut; ?>'>Telefono</a></th>
        <th></th>
       </tr>
     </thead>
		 <tbody>
			<?php
			$sql="SELECT a.id,a.nombre,a.descripcion,a.contacto,a.telefono,a.email,a.rut,a.fecha_baja
					FROM adjudicatarios AS a
					WHERE (a.fecha_baja IS NULL)
          AND (a.rut LIKE '%".$rut."%'
          AND a.descripcion LIKE '%".$nombre."%'
					OR a.nombre LIKE '%".$nombre."%')
					ORDER BY ".$orden." ASC";
			$result = mysql_query($sql,$conex);
	    if(mysql_num_rows($result) != 0) {
    		while ($reg = mysql_fetch_array($result)) { ?>
    			<tr>
            <td><?php echo $reg['rut']; ?></td>
					 	<td><?php echo $reg['nombre']; ?></td>
						<td><?php echo $reg['descripcion']; ?></td>
            <td><?php echo $reg['email']; ?></td>
            <td><?php echo $reg['contacto']; ?></td>
            <td><?php echo $reg['telefono']; ?></td>
						<td style="text-align:right">
							<a href="adjudicatario.php?step=2&ID=<?php echo $reg['id']; ?>" class='btn btn-primary' title="Modificar" type='submit'>
								<i class='icon-edit icon-white'></i>
							</a>
							<?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
							<!--<a href="php/adjudicatario/DEL.php?DEL=<?php //echo $reg['id']; ?>" class='btn btn-danger' title="Baja de adjudicatario" type='submit'>-->
              <a href="adjudicatario.php?DEL=<?php echo $reg['id']; ?>" class='btn btn-danger' title="Baja de adjudicatario" type='submit'>
								<i class='icon-trash icon-white'></i>
							</a>
					 		<?php } ?>
						</td>
					</tr>
        <?php }
			} ?>
		</tbody>
	</table>
</div>

<?php  mysql_close($conex); ?>
