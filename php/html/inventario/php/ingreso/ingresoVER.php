<?php

if (isset($_GET["ID"])) {
  $id = $_GET["ID"];
} else {
	$id = "";
}

// OBTENGO EL DOCUMENTO
function getDocumento($id) {
	include "php/conexion.php";
	$sql = "SELECT d.id,
								 d.observacion,
								 d.emision,
                 d.recepcion,
								 ue.nombre,
								 ue.apellido,
                 ur.nombre,
								 ur.apellido,
                 orge.nombre,
								 orge.sigla,
                 orgr.nombre,
								 orgr.sigla,
								 e.nombre,
								 t.descripcion
					FROM documentos AS d
					INNER JOIN usuarios ue ON ue.id = d.id_usuario_emisor
          LEFT JOIN usuarios ur ON ur.id = d.id_usuario_receptor
          INNER JOIN organismos orge ON orge.id = d.id_organismo_emisor
          LEFT JOIN organismos orgr ON orgr.id = d.id_organismo_receptor
					INNER JOIN tipos_documento t ON t.id = d.id_tipo_documento
					INNER JOIN estados_documento e ON e.id = d.id_estado
					WHERE d.id = $id";
	$result = mysql_query($sql,$conex);
	if(mysql_num_rows($result) != 0) {
		$reg = mysql_fetch_row($result);
		/*echo $reg[0];*/
	}
	mysql_close($conex);
  return $reg;
}

// OBTENGO EL DETALLE DEL DOCUMENTO
function getDetalle($id) {
	include "php/conexion.php";
	$sql = "SELECT d.id,
                 d.observacion,
                 d.cantidad,
                 a.fecha_baja,
								 a.nombre,
								 a.descripcion,
                 f.nombre,
								 f.descripcion
          FROM detalles_documento AS d
					INNER JOIN articulos a ON a.id = d.id_articulo
          INNER JOIN familias f ON f.id = a.id_familia
          INNER JOIN movimientos m ON m.id_detalle = d.id
          WHERE m.id_documento = $id";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

$documento = getDocumento($id);
$detalle = getDetalle($id);
?>


<?php if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>

<div class="row">
  <div class="col-md-8">
    <h3 class="muted text-left">Detalle del documento de ingreso Nro <?php echo $documento[0]; ?></h3>
  </div>
  <div class="col-md-4 no-print">
    <button id="printbutton" onclick="window.print();" class="btn btn-info" type="button" style="margin-top:-40px;margin-bottom:10px;margin-right:95px;float:right;">
      Imprimir <i class="icon-print icon-white"></i>
    </button>
    <a class='btn' href="ingreso.php" title="Volver" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Volver</a>
  </div>
</div>

<div class="row" media="print" >
  <div class="col-md-12">
    <table class="table table-bordered">
      <thead>
      </thead>
      <tbody>
        <tr>
          <th>#</th>
          <td><?php echo $documento[0]; ?></td>
          <th>Estado</th>
          <td><?php echo $documento[12]; ?></td>
          <th>Tipo</th>
          <td><?php echo $documento[13]; ?></td>
        </tr>
        <tr class="success">
          <th>Emisi&oacute;n</th>
          <td><?php echo $documento[2]!="" ? date("d-m-Y H:m", strtotime($documento[2])) : ""; ?></td>
          <td><?php echo $documento[4]." ".$documento[5]; ?></td>
          <td colspan="3"><?php echo $documento[8]." - ".$documento[9]; ?></td>
        </tr>
        <tr class="warning">
          <th>Recepci&oacute;n</th>
          <td><?php echo $documento[3]!="" ? date("d-m-Y H:m", strtotime($documento[3])) : ""; ?></td>
          <td><?php echo $documento[6]." ".$documento[7]; ?></td>
          <td colspan="3"><?php echo $documento[10]." - ".$documento[11]; ?></td>
        </tr>
        <tr class="info">
          <th>Observaci&oacute;n</th>
          <td colspan="5"><?php echo $documento[1]; ?></td>
        </tr>

      </tbody>
    </table>
  </div>
</div>

<?php if(mysql_num_rows($detalle) == 0) { ?>
<div class="alert alert-warning">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Documento vacío:</strong> Sin items aún, vuelva a la pantalla de ingreso y edite el documento para agregar items.
</div>
<?php } else { ?>
<div class="row" media="print" >
  <div class="col-md-12">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Art&iacute;culo</th>
          <th>Descripci&oacute;n</th>
          <th>Familia</th>
          <th>Cantidad</th>
          <th>Observaci&oacute;n</th>
          <th class="no-print"></th>
        </tr>
      </thead>
      <tbody>
      <?php while ($linea = mysql_fetch_array($detalle)) { ?>
      <tr>
        <td><?php echo $linea[0]; ?></td>
        <td><?php echo $linea[4]; ?></td>
        <td><?php echo $linea[5]; ?></td>
        <td><?php echo $linea[6]; ?></td>
        <td><?php echo $linea[2]; ?></td>
        <td><?php echo $linea[1]; ?></td>
        <td class="no-print">
          <a href='ingreso.php?step=5&ID=<?php echo $linea[0] ?>' class='btn btn-default' title="Ver informaci&oacute;n del &iacute;tem" type='submit'>
            <i class='icon-info-sign'></i>
          </a>
        </td>
      </tr>
      <?php } // FIN DWL WHILE ?>
      </tbody>
    </table>
  </div>
</div>

<?php } // FIN IF IF EXIST ITEMS ?>
<?php } // FIN IF DE PERMISOS ?>
