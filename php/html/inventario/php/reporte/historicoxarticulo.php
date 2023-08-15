
<?php if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {

if (isset($_GET["ORG"])) {
	$ORGANISMO = $_GET["ORG"];
} else {
	$ORGANISMO = "";
}
if (isset($_GET["ART"])) {
	$ARTICULO = $_GET["ART"];
} else {
	$ARTICULO = "";
}

if (isset($_GET["INI"])) {
	$INICIO = $_GET["INI"];
} else {
	$INICIO = "";
}
if (isset($_GET["FIN"])) {
	$FIN = $_GET["FIN"];
} else {
	$FIN = "";
}

if (isset($_GET["ORD"])) {
	$ORDEN = $_GET["ORD"];
} else {
	$ORDEN = "m.fecha";
}

// CARGO OPCIONES LOS ORGANISMOS DEL USUARIO LOGEADO
function opcionesOrganismo($organismo) {
	include "php/conexion.php";
	$sql = "SELECT o.id, o.nombre, o.sigla
					FROM organismos AS o WHERE o.fecha_baja IS NULL";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		if($organismo == $row[0]) {
			?><option value="<?php echo $row[0]; ?>" selected><?php echo $row[2]." - ".$row[1]; ?></option> <?php
		} else {
			?><option value="<?php echo $row[0]; ?>" ><?php echo $row[2]." - ".$row[1]; ?></option> <?php
		}
	} // End while
	mysql_close($conex);
}

// CARGO OPCIONES ARTICULOS
function opcionesArticulos($articulo) {
	include "php/conexion.php";
	$sql = "SELECT a.id, a.nombre, f.nombre
					FROM articulos AS a
					INNER JOIN familias f ON f.id = a.id_familia
					ORDER BY a.nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		if($articulo == $row[0]) {
			?><option value="<?php echo $row[0]; ?>" selected><?php echo $row[1]." - ".$row[2]; ?></option> <?php
		} else {
			?><option value="<?php echo $row[0]; ?>" ><?php echo $row[1]." - ".$row[2]; ?></option> <?php
		}
	} // End while
	mysql_close($conex);
}

function getArticulo($articulo) {
	include "php/conexion.php";
	$sql = "SELECT a.id, f.nombre, f.descripcion, a.nombre, a.descripcion, a.fecha_baja
					FROM articulos AS a
					INNER JOIN familias f ON f.id = a.id_familia
					WHERE a.id = $articulo
					ORDER BY a.nombre ASC";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
	return $result;
}

// OBTENGO EL DETALLE DEL DOCUMENTO
function getDetalle($organismo, $articulo, $inicio, $fin, $orden) {
	include "php/conexion.php";
	$sql = "SELECT
		a.id,
		f.nombre AS familia,
		a.nombre AS articulo_nombre,
		a.descripcion AS articulo_descripcion,
		dd.cantidad,
		orge.nombre AS emisor,
		orgr.nombre AS receptor,
		m.fecha,
		a.fecha_baja AS articulo_baja,
		f.descripcion AS familia_descripcion,
		m.observacion
		FROM movimientos AS m
		INNER JOIN documentos d ON d.id = m.id_documento
		INNER JOIN detalles_documento dd ON dd.id = m.id_detalle
		INNER JOIN articulos a ON a.id = dd.id_articulo
		INNER JOIN familias f ON f.id = a.id_familia
		INNER JOIN organismos orge ON orge.id = d.id_organismo_emisor
		INNER JOIN organismos orgr ON orgr.id = d.id_organismo_receptor
		WHERE (d.id_organismo_receptor = $organismo OR d.id_organismo_emisor = $organismo)
		AND dd.id_articulo = $articulo ";
 if ($inicio != "" && $fin != "") {
	 $sql .= "AND m.fecha BETWEEN '$inicio' AND '$fin' ";
 } else if ($inicio != "" && $fin == "") {
	 $sql .= "AND m.fecha >= '$inicio' ";
 } else if ($inicio == "" && $fin != "") {
	 $sql .= "AND m.fecha <= '$fin' ";
 }
 $sql .= "ORDER BY $orden ASC";
 $result = mysql_query($sql,$conex);
 mysql_close($conex);
 return $result;
}
$detalle = getDetalle($ORGANISMO, $ARTICULO, $INICIO, $FIN, $ORDEN);
$articulo = getArticulo($ARTICULO);
?>

<div class="row">
  <div class="col-md-8">
    <h3 class="muted text-left">Hist&oacute;rico por art&iacute;culo por unidad</h3>
  </div>
  <div class="col-md-4 no-print">
		<a href='php/reporte/historicoxarticulo_excel.php?ORG=<?php echo $ORGANISMO ?>&ART=<?php echo $ARTICULO ?>&INI=<?php echo $INICIO ?>&FIN=<?php echo $FIN ?>&ORD=<?php echo $ORDEN ?>' class="btn btn-success" type="button" style="margin-top:-50px;margin-bottom:20px;margin-right:110px;float:right;">
			Descargar Excel <i class="icon-download-alt icon-white"></i>
		</a>
    <button id="printbutton" onclick="window.print();" class="btn btn-info" type="button" style="margin-top:-50px;margin-bottom:20px;float:right;">
      Imprimir <i class="icon-print icon-white"></i>
    </button>
  </div>
</div>

<!-- Jumbotron -->
<div class="form-actions">
	<form id="form" class="form-inline" action="reporte.php?step=2" method="GET">
	<fieldset id="frm">
		<div style="padding-left: -10; padding: 10px;">
		<input type="text" name="step" style="width:130px;display:none;" value="2"></input>
		<select class="span4" name="ORG" title="Seleccione unidad">
			<option value="" selected>-- Seleccione unidad --</option>
			<?php opcionesOrganismo($ORGANISMO); ?>
		</select>
		<select class="span4" name="ART" title="Seleccione unidad">
			<option value="" selected>-- Seleccione art&iacute;culo --</option>
			<?php opcionesArticulos($ARTICULO); ?>
		</select>
	</div>
	<div style="padding-left: -10; padding: 10px;">
		<input type="date" name="INI" style="width:130px;" value="<?php echo $INICIO ?>"></input>
		<input type="date" name="FIN" style="width:130px;" value="<?php echo $FIN ?>"></input>
		<button class="btn btn-primary no-print" type="submit"><i class="icon-search icon-white"></i>&nbsp;&nbsp;Buscar</button>
	</div>
	</fieldset>
	</form>
</div>


<?php if(mysql_num_rows($detalle) == 0) { ?>

<div class="alert alert-info">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <h4>Sin resultados!</h4>
  No existen resultados que coincidan con su busqueda...
</div>

<?php } else { ?>

	<h4 class="muted text-left">Art&iacute;culo</h4>

	<div class="row" media="print">
	  <div class="col-md-12">
	    <table class="table table-striped">
	      <thead>
	        <tr class="info">
						<th>#</th>
						<th>Familia</th>
						<th>Fam. Descripci&oacute;n</th>
	          <th>Art&iacute;culo</th>
	          <th>Descripci&oacute;n</th>
						<th>Baja</th>
	        </tr>
	      </thead>
	      <tbody>
	        <?php
						while ($a = mysql_fetch_array($articulo)) { ?>
							<tr class="info">
								<td><?php echo $a[0]; ?></td>
								<td><?php echo $a[1]; ?></td>
								<td><?php echo $a[2]; ?></td>
	              <td><?php echo $a[3]; ?></td>
								<td><?php echo $a[4]; ?></td>
	              <td><?php echo $a[5]!="" ? date("d-m-Y H:m", strtotime($a[5])) : "Activo"; ?></td>
	            </tr> <?php
	          }
	        ?>
	      </tbody>
	    </table>
	  </div>
	</div>


<h4 class="muted text-left">Movimientos</h4>
<div class="row" media="print">
  <div class="col-md-12">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Emisor</th>
					<th>Receptor</th>
					<th>Cantidad</th>
					<th>Fecha</th>
					<th>Observaci&oacute;n</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(mysql_num_rows($detalle) != 0) {
					while ($linea = mysql_fetch_array($detalle)) { ?>
            <tr>
							<td><?php echo $linea[5]; ?></td>
							<td><?php echo $linea[6]; ?></td>
							<td><?php echo $linea[4]; ?></td>
							<td><?php echo $linea[7]!="" ? date("d-m-Y H:m", strtotime($linea[7])) : ""; ?></td>
							<td><?php echo $linea[10]; ?></td>
            </tr> <?php
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>

<?php } // FIN IF PERMISOS ?>
