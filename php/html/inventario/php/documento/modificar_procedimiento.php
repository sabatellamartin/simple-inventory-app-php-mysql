<?php

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {

if (isset($_GET["ID"])) {
	$item_id = $_GET["ID"];
}

// CARGO OPCIONES TIPOS DE PROCEDIMIENTOS
function opcionesProcedimiento($id) {
	include "php/conexion.php";
	$sql = "SELECT t.id,
                 t.descripcion
				  FROM tipos_procedimiento AS t
					ORDER BY t.descripcion ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    if($id == $row[0]) {
			?><option value="<?php echo $row[0]; ?>" selected><?php echo $row[1]; ?></option> <?php
		} else {
			?><option value="<?php echo $row[0]; ?>" ><?php echo $row[1]; ?></option> <?php
		}
	}
	mysql_close($conex);
}

// CARGO OPCIONES ADJUDICATARIOS
function opcionesAdjudicatario($id) {
	include "php/conexion.php";
	$sql = "SELECT a.id,
								 a.nombre,
                 a.descripcion
				  FROM adjudicatarios AS a
					ORDER BY a.nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    if($id == $row[0]) {
			?><option value="<?php echo $row[0]; ?>" selected><?php echo $row[1]; ?></option> <?php
		} else {
			?><option value="<?php echo $row[0]; ?>" ><?php echo $row[1]; ?></option> <?php
		}
	}
	mysql_close($conex);
}

// OBTENGO EL ITEM
function getItem($id) {
  include "php/conexion.php";

  $sql = "SELECT dd.id,
                 t.id,
                 ad.id,
                 dd.numero_factura,
                 dd.fecha_factura,
                 dd.plazo_garantia,
								 dd.numero_procedimiento,
                 a.nombre,
                 a.descripcion,
                 f.nombre,
                 dd.cantidad,
                 dd.codigo,
                 dd.observacion,
                 t.descripcion,
                 ad.nombre,
                 ad.descripcion,
                 ad.rut,
                 ad.telefono,
                 ad.contacto,
                 ad.email,
                 u.nombre,
                 u.apellido,
                 u.email,
                 dd.log_insert
          FROM detalles_documento AS dd
          LEFT JOIN usuarios u ON u.id = dd.log_usuario
          LEFT JOIN articulos a ON a.id = dd.id_articulo
          LEFT JOIN familias f ON f.id = a.id_familia
          LEFT JOIN tipos_procedimiento t ON t.id = dd.id_tipo_procedimiento
          LEFT JOIN adjudicatarios ad ON ad.id = dd.id_adjudicatario
          WHERE dd.id = $id";

  $result = mysql_query($sql,$conex);
  if(mysql_num_rows($result) != 0) {
    $reg = mysql_fetch_row($result);
    /*echo $reg[0];*/
  }
  mysql_close($conex);
  return $reg;
}

$item = getItem($item_id);
?>

<h3 class="muted">Actualizar procedimiento del item <?php echo $item[0] ?></h3>

<!-- Jumbotron -->
<div class="form-actions">
	<form class="form-inline" action="php/documento/UPD_procedimiento.php" method="POST" enctype="multipart/form-data">
	<fieldset>
		<div style="margin-left: 80px; padding-left: -10; padding: 10px;">

			<input class="span2" name="NRO" type="number" min='0' placeholder="Nro. procedimiento" value="<?php echo $item[6] ?>">

      <select class="span3" name="PRO" title="Seleccione tipo de procemidmiento" >
        <option value="" selected>-- Seleccione tipo de procemidmiento --</option>
        <?php opcionesProcedimiento($item[1]); ?>
      </select>

      <select class="span4" name="ADJ" title="Seleccione adjudicatario" >
        <option value="" selected>-- Seleccione adjudicatario --</option>
        <?php opcionesAdjudicatario($item[2]); ?>
      </select>
    </div>
    <div style="margin-left: 80px; padding-left: -10; padding: 10px;">

			<input class="span2" name="ID" type="text" placeholder="ITEM ID" value="<?php echo $item_id ?>" style="display:none;">
      <input class="span2" name="FAC" type="text" placeholder="Nro. de factura" value="<?php echo $item[3] ?>">
      <input class="span3" name="FEC" type="date" placeholder="Fecha de compra" value="<?php echo $item[4]!="" ? date("Y-m-d", strtotime($item[4])) : ""; ?>">
      <input class="span3" name="PLA" type="number" min='0' placeholder="Plazo de garant&iacute;a (d&iacute;as)" value="<?php echo $item[5] ?>">
    </div>
    <div style="margin-left: 80px; padding-left: -10; padding: 10px;">
  		<button class="btn btn-primary" type="submit"><i class="icon-pencil icon-white"></i>&nbsp;&nbsp;Actualizar</button>
    </div>
  </div>
	</fieldset>
	</form>
</div>


<?php } ?>
