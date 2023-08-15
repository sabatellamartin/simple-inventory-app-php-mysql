<?php

// OPCION DEPOSITO DE INGRESO
function opcionDepositoIngreso() {
	include "php/conexion.php";
	$sql = "SELECT o.id,
								 o.nombre,
								 o.sigla
	 				FROM organismos AS o
					WHERE o.sigla='D01'";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		?><option value="<?php echo $row[0] ?>" selected><?php echo $row[2]." - ".$row[1] ?></option> <?php
	} // end while
	mysql_close($conex);
}

// OBTENGO EL DETALLE DE LOS DOCUMENTOS DE INGRESO DE ITEMS
function getDetalle($inicio, $fin, $orden) {
	include "php/conexion.php";
	$sql = "SELECT
		f.nombre AS familia,
		sf.nombre AS subfamilia,
		a.nombre AS articulo_nombre,
		a.descripcion AS articulo_descripcion,
		SUM(CASE WHEN d.id_organismo_receptor = (SELECT o.id FROM organismos AS o WHERE o.sigla='D01') THEN dd.cantidad ELSE 0 END) AS cantidad,
		orge.nombre AS emisor,
		orgr.nombre AS receptor,
		a.fecha_baja AS articulo_baja,
		a.id
		FROM movimientos AS m
		INNER JOIN documentos d ON d.id = m.id_documento
		INNER JOIN detalles_documento dd ON dd.id = m.id_detalle
		INNER JOIN articulos a ON a.id = dd.id_articulo
		INNER JOIN familias sf ON sf.id = a.id_familia
		LEFT JOIN familias f ON f.id = sf.id_familia
		INNER JOIN organismos orge ON orge.id = d.id_organismo_emisor
		INNER JOIN organismos orgr ON orgr.id = d.id_organismo_receptor
		WHERE d.id_organismo_receptor = (SELECT o.id FROM organismos AS o WHERE o.sigla='D01')
		AND d.id_estado = (SELECT e.id FROM estados_documento AS e WHERE e.nombre LIKE '%Finalizado%') ";
 if ($inicio != "" && $fin != "") {
	 $sql .= "AND m.fecha BETWEEN '$inicio' AND '$fin' ";
 } else if ($inicio != "" && $fin == "") {
	 $sql .= "AND m.fecha >= '$inicio' ";
 } else if ($inicio == "" && $fin != "") {
	 $sql .= "AND m.fecha <= '$fin' ";
 }
 $sql .= "GROUP BY dd.id_articulo
 					ORDER BY $orden ASC";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

if (isset($_GET["ORG"])) {
	$ORGANISMO = $_GET["ORG"];
} else {
	$ORGANISMO = "";
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
	$ORDEN = "f.nombre";
}

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
<div class="row">
	<div class="col-8">
		<h3 class="muted text-left" style="margin-left:20px;">Items ingresados al D01</h3>
	</div>
	<div class="col-4 no-print">
    <button id="printbutton" onclick="window.print();" class="btn btn-info" type="button" style="margin-top:-50px;margin-bottom:20px;float:right;">
      Imprimir <i class="icon-print icon-white"></i>
    </button>
	</div>
</div>
<div class="alert alert-info no-print">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Items ingresados:</strong> Muestra el estado actual de los items ingresados y confirmados del depósito D01 disponibles para distribuir a las unidades. Además puede filtrar por la fecha en la que fueron ingresados e imprimir los resultados.
</div>
<!-- Jumbotron -->
<div class="form-actions">
	<form id="form" class="form-inline" action="reporte.php?step=1" method="GET">
	<fieldset id="frm">
		<div style="padding-left: -10; padding: 10px;">
			<input type="text" name="step" style="display:none;" value="3"></input>
			<select class="span4" name="ORG" title="Seleccione unidad">
				<?php opcionDepositoIngreso(); ?>
			</select>
			<input type="date" name="INI" style="width:130px;" value="<?php echo $INICIO ?>"></input>
			<input type="date" name="FIN" style="width:130px;" value="<?php echo $FIN ?>"></input>
			<button class="btn btn-primary no-print" type="submit"><i class="icon-search icon-white"></i>&nbsp;&nbsp;Buscar</button>
		</div>
	</fieldset>
	</form>
</div>
<?php $detalle = getDetalle($INICIO, $FIN, $ORDEN); ?>
<?php if(mysql_num_rows($detalle) == 0) { ?>
	<div class="alert alert-info">
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	  <h4>Sin resultados!</h4>
	  No existen resultados que coincidan con su busqueda...
	</div>
<?php } else { ?>
	<div class="row" media="print">
	  <div class="col-md-12">
	    <table class="table table-striped" style="margin-left:10px;">
	      <thead>
	        <tr>
	          <th>Familia&nbsp;<a class="no-print" href='reporte.php?step=3&FIN=<?php echo $FIN; ?>&INI=<?php echo $INICIO; ?>&ORD=f.nombre'><i class="icon-chevron-down"></i></a></th>
						<th>Subfamilia&nbsp;<a class="no-print" href='reporte.php?step=3&FIN=<?php echo $FIN; ?>&INI=<?php echo $INICIO; ?>&ORD=sf.nombre'><i class="icon-chevron-down"></i></a></th>
						<th>Art&iacute;culo&nbsp;<a class="no-print" href='reporte.php?step=3&FIN=<?php echo $FIN; ?>&INI=<?php echo $INICIO; ?>&ORD=a.nombre'><i class="icon-chevron-down"></i></a></th>
	          <th>Descripci&oacute;n&nbsp;<a class="no-print" href='reporte.php?step=3&FIN=<?php echo $FIN; ?>&INI=<?php echo $INICIO; ?>&ORD=a.descripcion'><i class="icon-chevron-down"></i></a></th>
						<th>Cantidad</th>
	        </tr>
	      </thead>
	      <tbody>
	        <?php while ($linea = mysql_fetch_array($detalle)) { ?>
	          <tr>
							<td><?php echo $linea[0]; ?></td>
							<td><?php echo $linea[1]; ?></td>
							<td><?php echo $linea[2]; ?></td>
	            <td><?php echo $linea[3]; ?></td>
	            <td><?php echo $linea[4]; ?></td>
	          </tr>
					<?php } ?>
	      </tbody>
	    </table>
	  </div>
	</div>
<?php } // FIN IF DETALLE RESULT ?>
<?php } // FIN IF PERMISOS ?>
