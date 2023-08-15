<?php

function opcionesUnidadReceptora($unidad_receptora) {
	include "php/conexion.php";
	$sql = "SELECT id, nombre, sigla FROM organismos";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    if($unidad_receptora == $row[0]) {
      ?><option value="<?php echo $row[0] ?>" selected><?php echo $row[2]." - ".$row[1] ?></option> <?php
    } else {
      ?><option value="<?php echo $row[0] ?>"><?php echo $row[2]." - ".$row[1] ?></option> <?php
    }
	} // end while
	mysql_close($conex);
}

function opcionesUnidadEmisora($id_usuario, $unidad_emisora) {
	include "php/conexion.php";
	$sql = "SELECT o.id,
								 o.nombre
	 				FROM usuarios_organismos AS uo
					LEFT JOIN organismos o ON o.id=uo.id_organismo
					WHERE uo.id_usuario='".$id_usuario."'";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    if($unidad_emisora == $row[0]) {
      ?><option value="<?php echo $row[0] ?>" selected><?php echo $row[1] ?></option> <?php
    } else {
      ?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option> <?php
    }
	} // end while
	mysql_close($conex);
}

function opcionesEstado($estado) {
	include "php/conexion.php";
	$sql = "SELECT id, nombre FROM estados_documento";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    if($estado == $row[0]) {
  		?><option value="<?php echo $row[0] ?>" selected><?php echo $row[1] ?></option> <?php
    } else {
      ?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option> <?php
    }
	} // end while
	mysql_close($conex);
}

function opcionesTipoDocumento($tipo) {
	include "php/conexion.php";
	$sql = "SELECT id, descripcion FROM tipos_documento";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    if($tipo == $row[0]) {
  		?><option value="<?php echo $row[0] ?>" selected><?php echo $row[1] ?></option> <?php
    } else {
      ?><option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option> <?php
    }
	} // end while
	mysql_close($conex);
}

// DOCUMENTO
function getDocumento($id) {
  include "php/conexion.php";
  $sql="SELECT id,
               id_tipo_documento,
               id_estado,
               id_organismo_emisor,
               id_usuario_emisor,
               id_organismo_receptor,
               observacion
  FROM documentos WHERE id=$id";
  $result = mysql_query($sql,$conex);
  while ($row = mysql_fetch_array($result)) {
    $documento = $row;
  } // end while
	mysql_close($conex);
  return $documento;
}

function getMovimientos($id) {
  include "php/conexion.php";
	$sql="SELECT f.nombre,
							 sf.nombre,
							 a.nombre,
							 a.descripcion,
							 dd.cantidad,
							 dd.id,
							 dd.id_articulo
				FROM detalles_documento AS dd
				INNER JOIN movimientos m ON m.id_detalle = dd.id
				INNER JOIN articulos a ON dd.id_articulo = a.id
				INNER JOIN familias sf ON a.id_familia = sf.id
				LEFT JOIN familias f ON sf.id_familia = f.id
				WHERE m.id_documento=$id";
  $result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

// OBTENGO LA INFORMACION DEL DOCUMENTO
$documento = getDocumento($_GET["ID"]);
// OBTENGO EL DETALLE DEL DOCUMENTO
$movimientos = getMovimientos($_GET["ID"]);
?>

<?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>

<div class="row">
	<div class="col-8">
		<h3 class="muted text-left" style="margin-left:20px;">Actualizando documento de ingreso nro <?php echo $_GET["ID"] ?></h3>
	</div>
	<div class="col-4">
    <a class='btn' href="ingreso.php" title="Volver" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Volver</a>
  </div>
</div>

<div class="alert alert-info">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Recuerde.</strong> Agregue items al documento a través de "Agregar item", puede modificar la observación y unidad receptora en caso de ser necesario.
  El documento permanecerá en "Proceso" hasta que sea "Finalizado", al realizar la acción de cambio de estado los items formaran parte del inventario.
</div>

<div class='form-actions'>
		<form id="frmConfiguracion" class="form-inline" action="php/ingreso/UPD.php" method="POST" enctype="multipart/form-data">
			<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
				<input class="span1" type="text" placeholder="ID" name="ID" value="<?php echo $documento[0] ?>" style="display:none;">
				<select class="span4" name="TIPO" title="Seleccione tipo" disabled>
					<option value="" selected disabled>-- Seleccione tipo --</option>
					<?php	opcionesTipoDocumento($documento[1]); ?>
				</select>
				<select class="span4" name="EST" title="Seleccione estado" disabled>
					<option value="" selected disabled>-- Seleccione estado --</option>
          <?php	opcionesEstado($documento[2]); ?>
				</select>
				<br></br>
        <select class="span4" name="ORGE" title="Seleccione unidad emisora" >
          <option value="" selected disabled>-- Seleccione unidad emisora --</option>
          <?php	opcionesUnidadEmisora($_SESSION['id_usuario'], $documento[3]); ?>
        </select>
        <select class="span4" name="ORGR" title="Seleccione unidad receptora" disabled>
					<option value="" selected disabled>-- Seleccione unidad receptora --</option>
          <?php	opcionesUnidadReceptora($documento[5]); ?>
				</select>
        <br></br>
				<input class='span8' type='text' placeholder='Observación' name="OBS" value="<?php echo $documento[6] ?>">
        <br></br>
        <button  type='submit' class='btn btn-warning'><i class='icon-edit icon-white'></i>&nbsp;Actualizar documento</button>
        <a href='ingreso.php?step=3&ID=<?php echo $documento[0] ?>' class='btn btn-primary' title="Agregar items">
          <i class='icon-plus icon-white'></i>&nbsp;Agregar item
        </a>
			</div>
	</form>
</div>

<?php if(mysql_num_rows($movimientos) == 0) { ?>
<div class="alert alert-warning">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Documento vacío:</strong> Agregue items al documento a través de "Agregar item".
</div>
<?php } else { ?>
<div class="tablaEditor">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th><a href='#'>Familia</a></th>
        <th><a href='#'>Subfamilia</a></th>
        <th><a href='#'>Nombre</a></th>
        <th><a href='#'>Descripción</a></th>
        <th><a href='#'>Cantidad</a></th>
        <th style="width:90px;"></th>
       </tr>
     </thead>
     <tbody>
  	 <?php while ($movimiento = mysql_fetch_array($movimientos)) { ?>
        <tr>
          <td><?php echo $movimiento[0] ?></td>
          <td><?php echo $movimiento[1] ?></td>
          <td><?php echo $movimiento[2] ?></td>
					<td><?php echo $movimiento[3] ?></td>
					<td><?php echo $movimiento[4] ?></td>
          <td style="text-align:right">
            <a href='ingreso.php?step=5&ID=<?php echo $movimiento[5] ?>' class='btn btn-default' title="Informaci&oacute;n del &iacute;tem">
							<i class='icon-info-sign'></i>
						</a>
            <a href='php/ingreso/itemDEL.php?DOC=<?php echo $_GET["ID"] ?>&DEL=<?php echo $movimiento[5] ?>' class='btn btn-danger' title="Borrar item">
              <i class='icon-trash icon-white'></i>
            </a>
          </td>
       </tr>
    <?php } // FIN DEL WHILE RECORRO MOVIMIENTOS ?>
    </tbody>
  </table>
</div>
<?php } // FIN DEL IF EXISTEN MOVIMIENTOS ?>
<?php } // FIN DE PERMISOS ?>
