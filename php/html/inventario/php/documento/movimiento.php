
<?php session_start();

// CARGO OPCIONES ORGANISMO EMISOR
function opcionesOrganismoEmisor($id) {
  include "php/conexion.php";
  $id_usuario = $_SESSION['id_usuario'];
	$sql = "SELECT o.id,
                 o.nombre
				  FROM organismos AS o
          INNER JOIN usuarios_organismos uo ON uo.id_organismo = o.id
          WHERE uo.id_usuario = $id_usuario
					ORDER BY o.nombre DESC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    ?><option value="<?php echo $row[0]; ?>" <?php echo $id==$row[0] ? "selected":""; ?> ><?php echo $row[1]; ?></option> <?php
	}
	mysql_close($conex);
}

// CARGO OPCIONES ORGANISMO RECEPTOR
function opcionesOrganismoReceptor($id) {
	include "php/conexion.php";
	$sql = "SELECT o.id,
                 o.nombre
				  FROM organismos AS o
					ORDER BY o.nombre DESC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    ?><option value="<?php echo $row[0]; ?>" <?php echo $id==$row[0] ? "selected":""; ?> ><?php echo $row[1]; ?></option> <?php
	}
	mysql_close($conex);
}

// CARGO OPCIONES TIPOS DE DOCUMENTO
function opcionesEstado($id) {
	include "php/conexion.php";
	$sql = "SELECT e.id,
                 e.nombre
				  FROM estados_documento AS e
					ORDER BY e.nombre DESC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    ?><option value="<?php echo $row[0]; ?>" <?php echo $id==$row[0] ? "selected":""; ?> ><?php echo $row[1]; ?></option> <?php
	}
	mysql_close($conex);
}

// CARGO OPCIONES TIPOS DE DOCUMENTO
function opcionesTipoDocumento($id) {
	include "php/conexion.php";
	$sql = "SELECT t.id,
                 t.descripcion
				  FROM tipos_documento AS t
					ORDER BY t.descripcion ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    ?><option value="<?php echo $row[0]; ?>" <?php echo $id==$row[0] ? "selected":""; ?> ><?php echo $row[1]; ?></option> <?php
	}
	mysql_close($conex);
}

// OBTENGO DOCUMENTO POR ID
function getDocumentoById($id) {
	include "php/conexion.php";
	$sql = "SELECT d.id,
								 d.id_tipo_documento,
								 d.id_estado,
								 d.id_organismo_emisor,
								 d.id_usuario_emisor,
                 d.id_organismo_receptor,
                 d.observacion
          FROM documentos AS d
          WHERE d.id=$id";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

// OBTENGO LOS ITEMS DEL MOVIMIENTO
function getItems($id_documento) {
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
                 orgr.nombre
          FROM detalles_documento AS dd
          INNER JOIN articulos a ON a.id = dd.id_articulo
          INNER JOIN familias f ON f.id = a.id_familia
          INNER JOIN movimientos m ON m.id_detalle = dd.id
          INNER JOIN documentos d ON d.id = m.id_documento
          INNER JOIN organismos orge ON orge.id = d.id_organismo_emisor
          INNER JOIN organismos orgr ON orgr.id = d.id_organismo_receptor
          WHERE (dd.fecha_baja IS NULL)
          AND m.id_documento = $id_documento
          ORDER BY a.nombre ASC";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

function eliminaMovimiento($id_documento, $id_detalle) {
  if ($id_detalle > 0 && $id_documento > 0 ) {
	   include "php/conexion.php";
     $sql  = "DELETE FROM movimientos WHERE id_documento=$id_documento AND id_detalle=$id_detalle";
 	   mysql_query($sql,$conex);
  }
}

// Agrego el item al movimiento
if (isset($_GET["DEL"]) && isset($_GET["ID"])) {
  eliminaMovimiento($_GET["ID"], $_GET["DEL"]);
}

if (isset($_GET["ID"])) {
  $id_documento = $_GET["ID"];
  $documento = getDocumentoById($id_documento);
  include "php/conexion.php";

  if(mysql_num_rows($documento) > 0) {
    while ($documento = mysql_fetch_array($documento)) {
      $doc = $documento;
      //var_dump($documento);
      $id = $doc[0];
  		$tipo = $doc[1];
  		$estado = $doc[2];
  		$orgE = $doc[3];
  		$usuE = $doc[4];
  		$orgR = $doc[5];
  		$obs = $doc[6];
    }
  }
}

?>

<h3 class="muted">Actualizar movimiento</h3>

	<div class='form-actions'>
			<form id="frmConfiguracion" class="form-inline" action="php/documento/UPD.php" method="POST" enctype="multipart/form-data">
				<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
					<input class="span1" type="text" placeholder="ID" name="ID" value="<?php echo $id ?>" style="display:none;">

          <!-- Combo Tipos de documento -->
          <select class="span4" name="TIPO" title="Seleccione tipo" disabled>
						<option value="" selected disabled>-- Seleccione tipo --</option>
            <?php opcionesTipoDocumento($tipo); ?>
					</select>

          <!-- Combo Estados de documento -->
          <select class="span4" name="EST" title="Seleccione estado" disabled>
						<option value="" selected disabled>-- Seleccione estado --</option>
            <?php opcionesEstado($estado); ?>
					</select>

					<br></br>

          <select class="span4" name="ORGE" title="Seleccione unidad emisora" >
            <option value="" selected disabled>-- Seleccione unidad emisora --</option>
            <?php opcionesOrganismoEmisor($orgE); ?>
          </select>
          <?php //if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario' || $_SESSION['tipo']=='operador') { ?>
					<select class="span4" name="ORG" title="Seleccione unidad receptora" disabled>
						<option value="" selected disabled>-- Seleccione unidad receptora --</option>
            <?php opcionesOrganismoReceptor($orgR); ?>
					</select>
          <?php// } ?>
          <br></br>
					<input class='span8' type='text' placeholder='Observación' name="OBS" value="<?php echo $obs?>">
          <br></br>
          <a href='documento.php' class="btn">
            <i class='icon-ban-circle'></i>&nbsp;Cancelar </a>
					<button  type='submit' class='btn btn-primary'><i class='icon-cog icon-white'></i>&nbsp;Guardar</button>
				</div>
		</form>
	</div>

	<div class="tablaEditor">

    <div style="text-align:right">

      <a href='documento.php?step=7&ID=<?php echo $id ?>' class='btn btn-info' title="Agregar items">
      	<i class='icon-plus icon-white'></i>&nbsp;Agregar &iacute;tem
  		</a>

    </div>

    <?php
    $items = getItems($id_documento, $familia, $nombre, $desc, $orden);
    include "php/conexion.php";
    if(mysql_num_rows($items) > 0) { ?>

    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Familia</th>
          <th>Nombre</th>
          <th>Descripci&oacute;n</th>
          <th>Cantidad</th>
          <th>Fecha movimiento</th>
          <th>Unidad destino</th>
          <th></th>
         </tr>
       </thead>
       <tbody>
    <?php
      while ($item = mysql_fetch_array($items)) { ?>
       <tr>
         <td><?php echo $item[1]; ?></td>
         <td><?php echo $item[2]; ?></td>
         <td><?php echo $item[3]; ?></td>
         <td><?php echo $item[5]; ?></td>
         <td><?php echo $item[8]!="" ? date("d-m-Y H:m", strtotime($item[8])) : ""; ?></td>
         <td><?php echo $item[10]; ?></td>
         <td>
           <a href='documento.php?step=6&ID=<?php echo $id ?>' class='btn btn-default' title="Ver informaci&oacute;n del &iacute;tem" type='submit'>
             <i class='icon-eye-open'></i>
           </a>
           <a href='documento.php?step=8&DEL=<?php echo $item[4]; ?>&ID=<?php echo $id_documento ?>' class='btn btn-danger' title="Eliminar artículo" type='submit'>
             <i class='icon-remove icon-white'></i>
           </a>
         </td>
         <?php
      } ?>
      </tbody>
    </table>
	</div>

<?php
} else { ?>
  <hr>
  <div class="alert alert-info">
    Para agregar un item al movimiento seleccione "Agregar &iacute;tem".
  </div>
  <?php
}
mysql_close($conex);
?>
