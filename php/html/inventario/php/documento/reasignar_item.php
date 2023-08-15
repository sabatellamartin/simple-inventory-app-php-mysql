<?php

// CARGO OPCIONES TIPOS DE PROCEDIMIENTOS
function opcionesProcedimiento() {
	include "php/conexion.php";
	$sql = "SELECT t.id,
                 t.descripcion
				  FROM tipos_procedimiento AS t
					ORDER BY t.descripcion ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
			?><option value="<?php echo $row[0]; ?>" ><?php echo $row[1]; ?></option> <?php
	}
	mysql_close($conex);
}

// CARGO OPCIONES ADJUDICATARIOS
function opcionesAdjudicatario() {
	include "php/conexion.php";
	$sql = "SELECT a.id,
								 a.nombre,
                 a.descripcion
				  FROM adjudicatarios AS a
					ORDER BY a.nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
			?><option value="<?php echo $row[0]; ?>" ><?php echo $row[1]; ?></option> <?php
	}
	mysql_close($conex);
}

// CARGO OPCIONES FAMILIA
function opcionesFamilia($id) {
	include "php/conexion.php";
	$sql = "SELECT f.id,
								 f.nombre
				  FROM familias AS f
					ORDER BY f.nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    ?><option value="<?php echo $row[0]; ?>" <?php echo $id==$row[0] ? "selected":""; ?> ><?php echo $row[1]; ?></option> <?php
	}
	mysql_close($conex);
}

// SEARCH
function searchItems($id_documento, $id_familia, $nombre, $descripcion, $orden) {
	include "php/conexion.php";
  $sql = "SELECT a.id,
                 f.nombre,
                 a.nombre,
                 a.descripcion,
                 dd.id,
                 dd.cantidad,
                 dd.codigo,
                 dd.observacion,
                 m.fecha,
                 orge.nombre,
                 orgr.nombre,
                 m.id_documento
          FROM detalles_documento AS dd
          INNER JOIN articulos a ON a.id = dd.id_articulo
          INNER JOIN familias f ON f.id = a.id_familia
          INNER JOIN movimientos m ON m.id_detalle = dd.id
          INNER JOIN documentos d ON d.id = m.id_documento
          INNER JOIN organismos orge ON orge.id = d.id_organismo_emisor
          INNER JOIN organismos orgr ON orgr.id = (SELECT id_organismo_emisor FROM documentos WHERE id = $id_documento)
          WHERE (dd.fecha_baja IS NULL)
          AND m.fecha = (SELECT MAX(fecha) FROM movimientos WHERE id_detalle = m.id_detalle)
					AND d.id_estado = 7
          AND (a.nombre LIKE '%".$nombre."%'
          OR a.descripcion LIKE '%".$descripcion."%'
          OR a.id_familia LIKE '%".$id_familia."%')
          ORDER BY $orden ASC";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

// AGREGO ITEM AL MOVIMIENTO
function guardaMovimiento($id_documento, $id_detalle) {
  include "php/conexion.php";
  if ($id_detalle > 0 && $id_documento > 0 ) {
    $sql = "SELECT m.id
            FROM movimientos AS m
            WHERE m.id_documento=$id_documento
            AND m.id_detalle=$id_detalle";
  	$result = mysql_query($sql,$conex);
  	if(mysql_num_rows($result) == 0) {
			$sql  = "UPDATE detalles_documento SET id_oficina=NULL WHERE id=$id_detalle";
			$result = mysql_query($sql,$conex);

      $sql  = "INSERT INTO movimientos (id_documento,id_detalle,fecha)
               VALUES ($id_documento, $id_detalle, '".date("Y-m-d H:i:s")."')";
      $result = mysql_query($sql,$conex);
    }
  }
  mysql_close($conex);
}

// Agrego el item al movimiento
if (isset($_GET["ID"]) && isset($_GET["ITEM"])) {
  guardaMovimiento($_GET["ID"], $_GET["ITEM"]);
  // Go back
  header("Location: documento.php?step=8&ID=".$id_documento);
}

$id_documento = $_GET["ID"];

if (isset($_GET["ORD"])) {
  $orden = $_GET["ORD"];
} else {
  $orden = "a.nombre";
}
if (isset($_GET["NOM"])) {
 $nombre = $_GET["NOM"];
} else {
 $nombre = "";
}
if (isset($_GET["FAM"])) {
 $familia = $_GET["FAM"];
} else {
 $familia = "";
}
if (isset($_GET["DES"])) {
 $desc = $_GET["DES"];
} else {
 $desc = "";
}

?>

<!-- CABEZAL -->
<div class="row">
  <div class="span8">
    <h3 class="muted">Reasignar item del inventario</h3>
  </div>
  <div class="span3">
    <a class='btn btn-primary' style="margin-top:20px;float:right;" type='submit' href='documento.php?step=8&ID=<?php echo $id_documento ?>' title="Volver"><i class='icon-arrow-left icon-white'></i>&nbsp;&nbsp;Volver</a>
  </div>
</div>


<!-- FORMULARIO -->
<div class="form-actions">
  <form id="form" class="form-inline" action="documento.php" method="GET">
    <fieldset id="frm">
      <div style="margin-left: 80px; padding-left: -10; padding: 10px;">
        <input type="text" name="step" value="7" style="display:none;">
        <input type="text" name="ID" value="<?php echo $id_documento ?>" style="display:none;">
        <select class="span4" name="FAM" title="Seleccione familia">
         <option value="" selected disabled>-- Seleccione familia --</option>
         <?php opcionesFamilia($familia); ?>
        </select>
        <input name='NOM' class='span4' type='text' value='<?php echo $nombre; ?>' placeholder='Nombre'>
        <br></br>
        <input name='DES' class='span4' type='text' value='<?php echo $desc; ?>' placeholder='Descripción'>
        <button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i>&nbsp;&nbsp;Buscar</button>
      </div>
    </fieldset>
  </form>
</div>

<hr>

<!-- LISTA -->
<div class="tablaEditor">
  <table class="table table-striped table-hover">
    <thead>
     <tr>
       <th><a href='documento.php?step=7&ORD=f.nombre&ID=<?php echo $id_documento; ?>&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Familia</a></th>
       <th><a href='documento.php?step=7&ORD=a.nombre&ID=<?php echo $id_documento; ?>&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Nombre</a></th>
       <th><a href='documento.php?step=7&ORD=a.descripcion&ID=<?php echo $id_documento; ?>&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Descripci&oacute;n</a></th>
       <th><a href='documento.php?step=7&ORD=dd.cantidad&ID=<?php echo $id_documento; ?>&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Cantidad</a></th>
       <th><a href='documento.php?step=7&ORD=orge.nombre&ID=<?php echo $id_documento; ?>&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Organismo anterior</a></th>
			 <th><a href='documento.php?step=7&ORD=m.fecha&ID=<?php echo $id_documento; ?>&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Último movimiento</a></th>
			 <th></th>
      </tr>
    </thead>
    <tbody>
    <?php
      $items = searchItems($id_documento, $familia, $nombre, $desc, $orden);
      include "php/conexion.php";
      if(mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) { ?>
         <tr>
           <td><?php echo $item[1]; ?></td>
           <td><?php echo $item[2]; ?></td>
           <td><?php echo $item[3]; ?></td>
           <td><?php echo $item[5]; ?></td>
           <td><?php echo $item[9]; ?></td>
					 <td><?php echo $item[8]!="" ? date("d-m-Y H:m", strtotime($item[8])) : ""; ?></td>
					 <td style="text-align:right">
             <?php if ($id_documento != $item[11]) { ?>
             <a href='documento.php?step=7&ID=<?php echo $id_documento ?>&ITEM=<?php echo $item[4] ?>' class='btn btn-info' title="Reasignar item" type='submit'>
               <i class='icon-share-alt icon-white'></i>
             </a>
             <?php } else { ?>
               <a href='documento.php?step=7&ID=<?php echo $id_documento ?>&ITEM=<?php echo $item[4] ?>' class='btn btn-success' title="&iacute;tem reasignado" disabled>
                 <i class='icon-ok icon-white'></i>
               </a>
             <?php } ?>
           </td>
        </tr>
        <?php
         }
      }
      mysql_close($conex);
      ?>
    </tbody>
  </table>
</div>
