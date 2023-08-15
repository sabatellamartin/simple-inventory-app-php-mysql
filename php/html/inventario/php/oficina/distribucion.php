<?php

$ORGANISMO = "";
if (isset($_GET["ORG"])) {
	$ORGANISMO = $_GET["ORG"];
}
$FILTRO = "";
if (isset($_GET["FIL"])) {
	$FILTRO = $_GET["FIL"];
}
$ORDEN = "m.fecha";
if (isset($_GET["ORD"])) {
	$ORDEN = $_GET["ORD"];
}

// CARGO OPCIONES LOS ORGANISMOS DEL USUARIO LOGEADO
function opcionesOrganismo($organismo) {
	include "php/conexion.php";
	$id_usuario = $_SESSION['id_usuario'];
	$sql = "SELECT o.id, o.nombre, o.sigla
					FROM usuarios_organismos uo
					INNER JOIN organismos o ON o.id = uo.id_organismo
					WHERE uo.id_usuario = $id_usuario";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		if($organismo == $row[0]) {
			?><option value="<?php echo $row[0]; ?>" selected><?php echo $row[1]." - ".$row[2]; ?></option> <?php
		} else {
			?><option value="<?php echo $row[0]; ?>" ><?php echo $row[1]." - ".$row[2]; ?></option> <?php
		}
	} // End while
	mysql_close($conex);
}

function getItemsByUnidad($organismo, $filtro, $orden) {
	include "php/conexion.php";
	$sql = "SELECT o.nombre AS oficina,
								 m.fecha,
								 orge.nombre AS emisor,
								 orgr.nombre AS receptor,
								 f.nombre,
								 a.nombre,
								 dd.cantidad,
								 dd.id
					FROM
					( SELECT m.id,m.id_documento,m.id_detalle,m.observacion, MAX(m.fecha) AS fecha
					  FROM movimientos AS m
					  GROUP BY m.id_detalle
					) AS m
					INNER JOIN documentos d ON d.id = m.id_documento
					INNER JOIN detalles_documento dd ON dd.id = m.id_detalle
					INNER JOIN organismos orge ON orge.id = d.id_organismo_emisor
					INNER JOIN organismos orgr ON orgr.id = d.id_organismo_receptor
					INNER JOIN articulos a ON a.id = dd.id_articulo
					INNER JOIN familias f ON a.id_familia = f.id
					LEFT JOIN oficinas o ON o.id = dd.id_oficina
					WHERE d.id_estado = 6 /*Confirmado*/
					AND orgr.id = $organismo
					AND (a.nombre LIKE '%$filtro%'
					OR a.descripcion LIKE '%$filtro%'
					OR f.nombre LIKE '%$filtro%')
					ORDER BY $orden DESC";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

?>

<div class="row">
	<div class="col-8">
		<h3 class="muted text-left" style="margin-left:20px;">Distribuci&oacute;n de inventario</h3>
	</div>
	<div class="col-4">
		<?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario' || $_SESSION['tipo']=='operador oficina' || $_SESSION['tipo']=='operador') { ?>
		<a class='btn no-print' href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Atr&aacute;s" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Atr&aacute;s</a>
		<a class='btn btn-primary no-print' href='oficina.php?step=2' title="Administrar oficinas" style="margin-top:-40px;margin-bottom:10px;float:right;margin-right:90px;"><i class='icon-pencil icon-white'></i>&nbsp;&nbsp;Administrar oficinas</a>
		<button id="printbutton" onclick="window.print();" class="btn btn-info no-print" type="button" style="margin-top:-40px;margin-bottom:10px;float:right;margin-right:270px;">
      Imprimir <i class="icon-print icon-white"></i>
    </button>
		<?php } ?>
	</div>
</div>

<!-- Jumbotron -->
<div class="form-actions">
	<form id="form" class="form-inline" action="oficina.php" method="GET">
	<fieldset id="frm">
		<div style="margin-left: 80px; padding-left: -10;">

			<input class="span1" name="step" style="display:none;" type="text" placeholder="step" value="1">

			<div class="input-prepend input-append">
				<span class="add-on"><i class='icon-chevron-down'></i></span>
				<select class="span4" name="ORG" title="Seleccione unidad">
					<option value="" selected>-- Seleccione unidad --</option>
					<?php opcionesOrganismo($ORGANISMO); ?>
				</select>
			</div>

			<div class="input-prepend input-append">
				<span class="add-on"><i class='icon-filter'></i></span>
				<input class="span3" name="FIL" type="text" placeholder="Filtro por texto" value="<?php echo $FILTRO; ?>">
			</div>

			<button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i>&nbsp;&nbsp;Buscar</button>

		</div>
	</fieldset>
	</form>
</div>

<hr>
<?php
$items = getItemsByUnidad($ORGANISMO, $FILTRO, $ORDEN);
if(mysql_num_rows($items) == 0) { ?>
	<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<h4>Sin resultados!</h4>
		No existen resultados que coincidan con su busqueda...
	</div> <?php
} else { ?>
<div class="tablaEditor">
	<table class="table table-striped table-hover">
		<thead>
	  	<tr>
				<th>Familia&nbsp;<a class="no-print" href='oficina.php?step=1&ORG=<?php echo $ORGANISMO; ?>&FIL=<?php echo $FILTRO; ?>&ORD=f.nombre'><i class="icon-chevron-down"></i></a></th>
				<th>Art&iacute;culo&nbsp;<a class="no-print" href='oficina.php?step=1&ORG=<?php echo $ORGANISMO; ?>&FIL=<?php echo $FILTRO; ?>&ORD=a.nombre'><i class="icon-chevron-down"></i></a></th>
				<th>Cantidad&nbsp;<a class="no-print" href='oficina.php?step=1&ORG=<?php echo $ORGANISMO; ?>&FIL=<?php echo $FILTRO; ?>&ORD=dd.cantidad'><i class="icon-chevron-down"></i></a></th>
				<th>Oficina&nbsp;<a class="no-print" href='oficina.php?step=1&ORG=<?php echo $ORGANISMO; ?>&FIL=<?php echo $FILTRO; ?>&ORD=o.nombre'><i class="icon-chevron-down"></i></a></th>
				<th>Ubicaci&oacute;n anterior&nbsp;<a class="no-print" href='oficina.php?step=1&ORG=<?php echo $ORGANISMO; ?>&FIL=<?php echo $FILTRO; ?>&ORD=orge.nombre'><i class="icon-chevron-down"></i></a></th>
				<th>Último movimiento&nbsp;<a class="no-print" href='oficina.php?step=1&ORG=<?php echo $ORGANISMO; ?>&FIL=<?php echo $FILTRO; ?>&ORD=m.fecha'><i class="icon-chevron-down"></i></a></th>
				<th></th>
	     </tr>
	   </thead>
		 <tbody>
			<?php
				while ($item = mysql_fetch_array($items)) { ?>
						<tr>
						<td><?php echo $item[4] ?></td>
						<td><?php echo $item[5] ?></td>
						<td><?php echo $item[6] ?></td>
						<td><?php echo $item[0]!="" ? $item[0] : "-" ?></td>
						<td><?php echo $item[2] ?></td>
						<td><?php echo $item[1]!="" ? date("d-m-Y H:m", strtotime($item[1])) : "" ?></td>
						<td style="text-align:right">
							<a href='oficina.php?step=3&ID=<?php echo $item[7] ?>' class='btn btn-info no-print' title="Asignar oficina" type='submit'>
								<i class='icon-map-marker icon-white'></i>
							</a>
							<a href='documento.php?step=6&ID=<?php echo $item[7] ?>' class='btn btn-default' title="Ver informaci&oacute;n del &iacute;tem" type='submit'>
								<i class='icon-eye-open'></i>
							</a>
						</td>
					</tr>
			<?php
	    	} ?>
		</tbody>
	</table>
</div>
<?php } ?>
