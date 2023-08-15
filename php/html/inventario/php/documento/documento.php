<?php
if (isset($_GET["ORD"])) {
	$orden = $_GET["ORD"];
} else {
	$orden = "d.emision";
}
if (isset($_GET["EST"])) {
	$ESTADO = $_GET["EST"];
} else {
	$ESTADO = "";
}
if (isset($_GET["ORG"])) {
	$ORGANISMO = $_GET["ORG"];
} else {
	$ORGANISMO = "";
}

function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ) {
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
    $interval = date_diff($datetime1, $datetime2);
    return $interval->format($differenceFormat);
}

function alertaVencimientoDocumento($fecha, $estado) {
	$now = date("Y-m-d H:i:s");
	$diff = dateDifference($fecha, $now,'%a');
	if ($diff > 0 && $diff <= 10 && $estado!='Finalizado') {
		return 1;
	} else if ($diff > 10 && $diff <= 20 && $estado!='Finalizado') {
		return 2;
	} else if ($diff > 20 && $estado!='Finalizado') {
		return 3;
	} else {
		return 0;
	}
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

// CARGO OPCIONES ESTADO
function opcionesEstado($estado) {
	include "php/conexion.php";
	$sql = "SELECT e.id,
								 e.nombre
				  FROM estados_documento AS e
					ORDER BY e.nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		if($estado == $row[0]) {
			?><option value="<?php echo $row[0]; ?>" selected><?php echo $row[1]; ?></option> <?php
		} else {
			?><option value="<?php echo $row[0]; ?>" ><?php echo $row[1]; ?></option> <?php
		}
	}
	mysql_close($conex);
}
// OBTENGO LOS DOCUMENTOS SEGUN LOS FILTROS
function getDocumentos($estado, $organismo, $orden) {
	include "php/conexion.php";
	$sql = "SELECT d.id,
								 d.observacion,
								 d.emision,
								 t.descripcion,
								 e.nombre,
								 u.nombre,
								 u.apellido,
								 t.id,
								 o.nombre,
								 o.sigla,
								 u.id
          FROM documentos AS d
					INNER JOIN tipos_documento t ON t.id = d.id_tipo_documento
          INNER JOIN estados_documento e ON e.id = d.id_estado
					INNER JOIN organismos o ON o.id = d.id_organismo_emisor
					INNER JOIN usuarios u ON u.id = d.id_usuario_emisor ";
					if ($estado != "" && $organismo != "") {
						$sql .= "WHERE (d.id_organismo_emisor = $organismo ";
						$sql .= "OR d.id_organismo_receptor = $organismo) ";
						$sql .= "AND d.id_estado = $estado ";
					} else if ($estado == "" && $organismo != "") {
						$sql .= "WHERE d.id_organismo_emisor = $organismo ";
						$sql .= "OR d.id_organismo_receptor = $organismo ";
					} else if ($estado != "" && $organismo == "") {
						$sql .= "WHERE d.id_estado = $estado ";
					} else if ($estado == "" && $organismo == "") {
						if ($_SESSION['tipo']!='administrador' || $_SESSION['tipo']!='operador inventario') {
							$id_usuario = $_SESSION['id_usuario'];
							$sql .= "WHERE d.id_organismo_emisor IN (SELECT uo.id_organismo FROM usuarios_organismos uo WHERE uo.id_usuario = $id_usuario) ";
							$sql .= "OR d.id_organismo_receptor IN (SELECT uo.id_organismo FROM usuarios_organismos uo WHERE uo.id_usuario = $id_usuario) ";
						}
					}
	$sql .= "ORDER BY $orden DESC";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

?>

<div class="row">
	<div class="col-8">
		<h3 class="muted text-left" style="margin-left:20px;">Movimientos</h3>
	</div>
	<div class="col-4">
		<?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario' || $_SESSION['tipo']=='operador oficina' || $_SESSION['tipo']=='operador') { ?>
		<a class='btn' href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Atr&aacute;s" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Atr&aacute;s</a>
		<a class='btn btn-primary' href='documento.php?step=1' title="Agregar documento" style="margin-top:-40px;margin-bottom:10px;float:right;margin-right:90px;"><i class='icon-plus-sign icon-white'></i>&nbsp;&nbsp;Agregar</a>
		<?php } ?>
	</div>
</div>

<!-- Jumbotron -->
<div class="form-actions">
	<form id="form" class="form-inline" action="documento.php" method="GET">
	<fieldset id="frm">
		<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
		<select class="span4" name="ORG" title="Seleccione unidad">
			<option value="" selected>-- Seleccione unidad --</option>
			<?php opcionesOrganismo($ORGANISMO); ?>
		</select>
		<select class="span4" name="EST" title="Seleccione estado">
			<option value="" selected>-- Seleccione estado --</option>
			<?php opcionesEstado($ESTADO); ?>
		</select>
		<button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i>&nbsp;&nbsp;Buscar</button>
	</div>
	</fieldset>
	</form>
</div>

<hr>

<div class="tablaEditor">
	<table class="table table-striped table-hover">
		<thead>
	  	<tr>
				<th><a href='documento.php?ORD=d.id&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>ID</a></th>
	      <th><a href='documento.php?ORD=t.descripcion&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Tipo</a></th>
	      <th><a href='documento.php?ORD=e.nombre&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Estado</a></th>
				<th><a href='documento.php?ORD=u.nombre&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Emisor</a></th>
				<th><a href='documento.php?ORD=o.sigla&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Unidad</a></th>
				<th><a href='documento.php?ORD=d.emision&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Emisión</a></th>
				<th><a href='documento.php?ORD=d.observacion&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Observación</a></th>
				<th></th>
	     </tr>
	   </thead>
		 <tbody>
			<?php
			$documentos = getDocumentos($ESTADO, $ORGANISMO, $orden);
			if(mysql_num_rows($documentos) != 0) {
				while ($documento = mysql_fetch_array($documentos)) {
					$flag = alertaVencimientoDocumento($documento[2], $documento[4]); ?>
					<?php if ($flag == 0) { ?>
						<tr>
					<?php } else if ($flag == 1) { ?>
						<tr class="info">
					<?php } else if ($flag == 2) { ?>
						<tr class="warning">
					<?php } else if ($flag == 3) { ?>
						<tr class="error">
					<?php } ?>
						<td><?php echo $documento[0]; ?></td>
					 	<td><?php echo $documento[3]; ?></td>
						<td><?php echo $documento[4]; ?></td>
						<td><?php echo $documento[5]." ".$documento[6]; ?></td>
						<td><?php echo $documento[9]; ?></td>
						<td><?php echo $documento[2]!="" ? date("d-m-Y H:m", strtotime($documento[2])) : ""; ?></td>
						<td><?php echo $documento[1]; ?></td>
						<td style="text-align:right">
							<a href='documento.php?step=3&ID=<?php echo $documento[0] ?>' class='btn btn-default' title="Ver detalle del documento" type='submit'>
								<i class='icon-eye-open'></i>
							</a>
							<?php if($documento[4]=="Confirmado" && $_SESSION['tipo']=='autorizador') { ?>
							<a href='documento.php?step=4&ID=<?php echo $documento[0] ?>&TIPO=1' class='btn btn-info' title="Finalizar" type='submit'>
								Finalizar <i class='icon-flag icon-white'></i>
							</a>
							<a href='documento.php?step=4&ID=<?php echo $documento[0] ?>&TIPO=2' class='btn btn-danger' title="Rechazar" type='submit'>
								Rechazar <i class='icon-flag icon-white'></i>
							</a>
						 	<?php } ?>
							<?php if($documento[4]=="Autorizado" && ($_SESSION['tipo']=='operador oficina' || $_SESSION['tipo']=='operador')) { ?>
							<a href='documento.php?step=4&ID=<?php echo $documento[0] ?>&TIPO=1' class='btn btn-info' title="Confirmar" type='submit'>
								Confirmar <i class='icon-flag icon-white'></i>
							</a>
							<a href='documento.php?step=4&ID=<?php echo $documento[0] ?>&TIPO=2' class='btn btn-danger' title="Rechazar" type='submit'>
								Rechazar <i class='icon-flag icon-white'></i>
							</a>
						 	<?php } ?>
							<?php if($documento[4]=="Proceso" && $_SESSION['tipo']=='autorizador') { ?>
							<a href='documento.php?step=4&ID=<?php echo $documento[0] ?>&TIPO=1' class='btn btn-info' title="Autorizar" type='submit'>
								Autorizar <i class='icon-flag icon-white'></i>
							</a>
							<a href='documento.php?step=4&ID=<?php echo $documento[0] ?>&TIPO=2' class='btn btn-danger' title="Rechazar" type='submit'>
								Rechazar <i class='icon-flag icon-white'></i>
							</a>
						 	<?php } ?>
							<?php if($documento[4]=="Inicial" && ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario' || $_SESSION['tipo']=='operador oficina')) { ?>
							<a href='documento.php?step=4&ID=<?php echo $documento[0] ?>&TIPO=1' class='btn btn-info' title="Procesar" type='submit'>
								Procesar <i class='icon-flag icon-white'></i>
							</a>
								<?php if($documento[3]=="Salida" && ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador oficina')) { ?>
								<a href='documento.php?step=8&ID=<?php echo $documento[0] ?>' class='btn btn-info' title="Editar documento" type='submit'>
									<i class='icon-edit icon-white'></i>
								</a>
								<?php } ?>
								<?php if($documento[3]=="Entrada" && ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario')) { ?>
								<a href='documento.php?step=2&ID=<?php echo $documento[0] ?>' class='btn btn-primary' title="Editar documento" type='submit'>
									<i class='icon-edit icon-white'></i>
								</a>
								<?php } ?>
								<?php if($documento[10]==$_SESSION['id_usuario']) { ?>
								<a href='php/documento/DEL.php?DEL=<?php echo $documento[0] ?>' class='btn btn-danger' title="Baja de documento" type='submit'>
									<i class='icon-trash icon-white'></i>
								</a>
								<?php } ?>
					 		<?php } ?>

						</td>
					</tr>
			<?php
	    	}
			} ?>
		</tbody>
	</table>
</div>

<div class="row" style="margin-top:10px">
	<div class="span3">
		<div class="alert alert-info">
			Menos de 10 d&iacute;as
		</div>
	</div>
	<div class="span3">
		<div class="alert">
		  Entre 10 y 20 d&iacute;as
		</div>
	</div>
	<div class="span3">
		<div class="alert alert-danger">
			M&aacute;s de 30 d&iacute;as
		</div>
	</div>
</div>
