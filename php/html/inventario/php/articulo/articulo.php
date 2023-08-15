<?php

function getFamiliaById($id) {
  include "php/conexion.php";
  $sql="SELECT id, id_familia, nombre, descripcion, codigo FROM familias WHERE id=$id";
  $res = mysql_query($sql,$conex);
  $f = mysql_fetch_array($res);
	mysql_close($conex);
  return $f;
}

function opcionesFamilia($familia) {
	include "php/conexion.php";
	$sql = "SELECT f.id,
								 f.nombre,
                 f.codigo
				  FROM familias AS f
          WHERE f.id_familia IS NULL
					ORDER BY f.nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		if($familia == $row[0]) {
			?><option value="<?php echo $row[0] ?>" selected><?php echo $row[2]." - ".$row[1] ?></option> <?php
		} else {
			?><option value="<?php echo $row[0] ?>" ><?php echo $row[2]." - ".$row[1] ?></option> <?php
		}
	}
	mysql_close($conex);
}

function opcionesSubfamilia($familia, $subfamilia) {
	include "php/conexion.php";
	$sql = "SELECT f.id,
								 f.nombre,
                 f.codigo
				  FROM familias AS f
          WHERE f.id_familia IS NOT NULL
          AND f.id_familia = $familia
					ORDER BY f.nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		if($subfamilia == $row[0]) {
			?><option value="<?php echo $row[0] ?>" selected><?php echo $row[2]." - ".$row[1] ?></option> <?php
		} else {
			?><option value="<?php echo $row[0] ?>" ><?php echo $row[2]." - ".$row[1] ?></option> <?php
		}
	}
	mysql_close($conex);
}

function messageDelete($id) {
	include "php/conexion.php";
	$sql="SELECT id, nombre FROM articulos WHERE id=$id";
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
  			<h3 id="myModalLabel">Eliminar artículo</h3>
  		</div>
  		<div class="modal-body">
  			<p>¿Esta seguro de eliminar el artículo <?php echo $nombre; ?>?</p>
  		</div>
  		<div class="modal-footer">
  			<a href='articulo.php' class="btn" aria-hidden="true">Cancelar</a>
  			<a href='php/articulo/DEL.php?DEL=<?php echo $id; ?>' class="btn btn-primary">Eliminar</a>
  		</div>
  	</div>
  <?php
}


$orden = "a.log_insert";
if (isset($_GET["ORD"])) {
   $orden = $_GET["ORD"];
}
if (isset($_GET["NOM"])) {
	$nombre = $_GET["NOM"];
} else {
	$nombre = "";
}
if (isset($_GET["DES"])) {
	$descripcion = $_GET["DES"];
} else {
	$descripcion = "";
}
if (isset($_GET["FAM"])) {
	$familia = $_GET["FAM"];
} else {
	$familia = "";
}
if (isset($_GET["SFAM"])) {
	$subfamilia = $_GET["SFAM"];
} else {
	$subfamilia = "";
}
?>
<div class="row">
	<div class="col-8">
		<h3 class="muted text-left" style="margin-left:20px;">Artículos</h3>
	</div>
	<div class="col-4">
		<?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
    <a class='btn' href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Atr&aacute;s" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Atr&aacute;s</a>
    <a class='btn btn-primary' href='articulo.php?step=1' title="Agregar artículo" style="margin-top:-40px;margin-bottom:10px;float:right;margin-right:90px;"><i class='icon-plus-sign icon-white'></i>&nbsp;&nbsp;Agregar</a>
    <?php } ?>
	</div>
</div>

<?php // Delete message
if(isset($_GET["DEL"])) {
  messageDelete($_GET["DEL"]);
} ?>

<?php if (isset($_GET["FAM"])){
	$f = getFamiliaById($_GET["FAM"]); ?>
	<div class="alert alert-info">
  	<strong><?php echo $f[2] ?></strong> <?php echo $f[3] ?>
	</div>
<?php }?>

<?php if (isset($_GET["SFAM"])){
	$sf = getFamiliaById($_GET["SFAM"]); ?>
	<div class="alert alert-info">
  	<strong><?php echo $sf[2] ?></strong> <?php echo $sf[3] ?>
	</div>
<?php }?>

<!-- Jumbotron -->
<div class="form-actions">
  <form id="form" class="form-inline" action="articulo.php" method="GET">
		<fieldset id="frm">
		<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
		<select class="span4" name="FAM" title="Busqueda por subfamilia" onchange="window.location.href='articulo.php?FAM='+this.value" >
			<option value="" selected disabled>-- Busqueda por familia --</option>
      <?php opcionesFamilia($familia); ?>
		</select>
    <select class="span4" name="SFAM" title="Busqueda por subfamilia" onchange="window.location.href='articulo.php?FAM=<?php echo $familia ?>&SFAM='+this.value" >
      <option value="" selected disabled>-- Busqueda por subfamilia --</option>
      <?php opcionesSubfamilia($familia, $subfamilia); ?>
    </select>
    <br></br>
		<input name='NOM' class='span4' type='text' value='<?php echo $nombre; ?>' placeholder='Nombre'>
		<input name='DES' class='span4' type='text' value='<?php echo $descripcion; ?>' placeholder='Descripción'>
    <br></br>
 		<button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i>&nbsp;&nbsp;Buscar</button>
    <a class="btn" href="articulo.php" type="button" onclick="resetBuscar()" value="Limpiar"><i class="icon-refresh"></i>&nbsp;&nbsp;Limpiar</a>
	</div>
 	</fieldset>
	</form>
</div>

<hr>

<div class="tablaEditor">
	<table class="table table-striped table-hover">
  	<thead>
	  	<tr>
        <th><a href='articulo.php?ORD=f.nombre&NOM=<?php echo $nombre; ?>&DES=<?php echo $descripcion; ?>&FAM=<?php echo $familia; ?>'>Familia</a></th>
  			<th><a href='articulo.php?ORD=a.nombre&NOM=<?php echo $nombre; ?>&DES=<?php echo $descripcion; ?>&FAM=<?php echo $familia; ?>'>Nombre</a></th>
        <th><a href='articulo.php?ORD=a.descripcion&NOM=<?php echo $nombre; ?>&DES=<?php echo $descripcion; ?>&FAM=<?php echo $familia; ?>'>Descripci&oacute;n</a></th>
        <th><a href='articulo.php?ORD=a.log_insert&NOM=<?php echo $nombre; ?>&DES=<?php echo $descripcion; ?>&FAM=<?php echo $familia; ?>'>Ingreso</a></th>
        <th><a href='articulo.php?ORD=a.fecha_baja&NOM=<?php echo $nombre; ?>&DES=<?php echo $descripcion; ?>&FAM=<?php echo $familia; ?>'>Baja</a></th>
        <th></th>
       </tr>
     </thead>
		 <tbody>
			<?php
      include "php/conexion.php";
			$sql="SELECT a.id,
                   a.id_familia,
                   a.nombre,
                   a.descripcion,
                   a.log_insert,
                   f.nombre,
                   a.fecha_baja
					FROM articulos AS a
          INNER JOIN familias f ON f.id = a.id_familia
					WHERE (fecha_baja IS NULL) AND (a.id_familia LIKE '%".$familia."%'
					AND a.nombre LIKE '%".$nombre."%'
          AND a.descripcion LIKE '%".$descripcion."%')
					ORDER BY ".$orden." DESC";
			$result = mysql_query($sql,$conex);
	    if(mysql_num_rows($result) != 0) {
    		while ($reg = mysql_fetch_array($result)) { ?>
    			<tr>
            <td><?php echo $reg[5] ?></td>
						<td><?php echo $reg[2] ?></td>
					 	<td><?php echo $reg[3] ?></td>
            <td><?php echo $reg[4]!="" ? date("d-m-Y H:m", strtotime($reg[4])) : "" ?></td>
            <td><?php echo $reg[6]!="" ? date("d-m-Y H:m", strtotime($reg[6])) : "Activo" ?></td>
						<td style="text-align:right">
							<a href='articulo.php?step=2&ID=<?php echo $reg[0] ?>' class='btn btn-primary' title="Modificar" type='submit'>
								<i class='icon-edit icon-white'></i>
							</a>
							<?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
							<!--<a href='php/articulo/DEL.php?DEL=<?php //echo $reg[0] ?>' class='btn btn-danger' title="Baja de artículo" type='submit'> -->
              <a href='articulo.php?DEL=<?php echo $reg[0] ?>' class='btn btn-danger' title="Baja de artículo" type='submit'>
								<i class='icon-circle-arrow-down icon-white'></i>
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
