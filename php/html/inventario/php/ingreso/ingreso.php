<?php

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

// CARGO OPCIONES ESTADOS INGRESO
function opcionesEstado($estado) {
	include "php/conexion.php";
	$sql = "SELECT e.id,
								 e.nombre
				  FROM estados_documento AS e
					WHERE e.nombre LIKE '%Proceso%'
					OR e.nombre LIKE '%Finalizado%'
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
					INNER JOIN usuarios u ON u.id = d.id_usuario_emisor
					WHERE d.id_organismo_receptor = (SELECT o.id FROM organismos o WHERE o.sigla = 'D01') ";
					if ($estado != "" && $organismo != "") {
						$sql .= "AND d.id_organismo_emisor = $organismo ";
						$sql .= "AND d.id_estado = $estado ";
					} else if ($estado == "" && $organismo != "") {
						$sql .= "AND d.id_organismo_emisor = $organismo ";
						$sql .= "AND d.id_estado IN (SELECT id FROM estados_documento WHERE nombre LIKE '%Proceso%' OR nombre LIKE '%Finalizado%') ";
					} else if ($estado != "" && $organismo == "") {
						$sql .= "AND d.id_organismo_emisor IN (SELECT uo.id_organismo FROM usuarios_organismos uo WHERE uo.id_usuario = ".$_SESSION['id_usuario'].") ";
						$sql .= "AND d.id_estado = $estado ";
					} else if ($estado == "" && $organismo == "") {
						$sql .= "AND d.id_organismo_emisor IN (SELECT uo.id_organismo FROM usuarios_organismos uo WHERE uo.id_usuario = ".$_SESSION['id_usuario'].") ";
						$sql .= "AND d.id_estado IN (SELECT id FROM estados_documento WHERE nombre LIKE '%Proceso%' OR nombre LIKE '%Finalizado%') ";
					}
	$sql .= "ORDER BY $orden DESC";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

if (isset($_GET["ORD"])) {
	$ORDEN = $_GET["ORD"];
} else {
	$ORDEN = "d.emision";
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
?>

<?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
<div class="row">
	<div class="col-8">
		<h3 class="muted text-left" style="margin-left:20px;">Ingreso de items</h3>
	</div>
	<div class="col-4">
		<a class='btn' href="main.php" title="Volver" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Volver</a>
		<a class='btn btn-primary' href='ingreso.php?step=1' title="Agregar documento" style="margin-top:-40px;margin-bottom:10px;float:right;margin-right:93px;"><i class='icon-plus-sign icon-white'></i>&nbsp;&nbsp;Crear documento de ingreso</a>
		<a class='btn' href='reporte.php?step=3' title="Items ingresados al D01" style="margin-top:-40px;margin-bottom:10px;float:right;margin-right:325px;"><i class='icon-list'></i>&nbsp;&nbsp;Items ingresados al D01</a>
	</div>
</div>

<div class="alert alert-info">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Ingreso de items:</strong> Destino de los items será en todos los casos el depósito de ingresos D01.
	Los items quedan confirmados para distribuir a las unidades cuando pasan a estado 'Finalizado'.
</div>


<!-- Jumbotron -->
<div class="form-actions">
	<form id="form" class="form-inline" action="ingreso.php" method="GET">
	<fieldset id="frm">
		<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
		<select class="span3" name="EST" title="Seleccione estado">
			<option value="" selected>-- Filtro por estado --</option>
			<?php opcionesEstado($ESTADO); ?>
		</select>
		<select class="span5" name="ORG" title="Seleccione unidad">
			<option value="" selected>-- Filtro por unidad --</option>
			<?php opcionesOrganismo($ORGANISMO); ?>
		</select>
		<br></br>
		<button class="btn btn-primary" type="submit"><i class="icon-filter icon-white"></i>&nbsp;&nbsp;Filtrar documentos</button>
    <a class="btn" href="ingreso.php" type="button" onclick="resetBuscar()" value="Limpiar"><i class="icon-refresh"></i>&nbsp;&nbsp;Limpiar</a>
	</div>
	</fieldset>
	</form>
</div>

<hr>

<div class="tablaEditor">
	<table class="table table-striped table-hover">
		<thead>
	  	<tr>
				<th><a href='ingreso.php?ORD=d.id&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>ID</a></th>
	      <th><a href='ingreso.php?ORD=t.descripcion&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Tipo</a></th>
	      <th><a href='ingreso.php?ORD=e.nombre&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Estado</a></th>
				<th><a href='ingreso.php?ORD=u.nombre&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Emisor</a></th>
				<th><a href='ingreso.php?ORD=o.sigla&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Unidad</a></th>
				<th><a href='ingreso.php?ORD=d.emision&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Emisión</a></th>
				<th><a href='ingreso.php?ORD=d.observacion&EST=<?php echo $ESTADO; ?>&ORG=<?php echo $ORGANISMO; ?>'>Observación</a></th>
				<th style="width:175px;"></th>
	     </tr>
	   </thead>
		 <tbody>
			<?php
			$documentos = getDocumentos($ESTADO, $ORGANISMO, $ORDEN);
			if(mysql_num_rows($documentos) != 0) {
				while ($documento = mysql_fetch_array($documentos)) { ?>
					<tr>
					  <td><?php echo $documento[0]; ?></td>
					 	<td><?php echo $documento[3]; ?></td>
						<td><?php if ($documento[4] == 'Proceso') { ?><p class="text-warning"><?php } else { ?><p class="text-success"><?php } ?><?php echo $documento[4] ?></p></td>
						<td><?php echo $documento[5]." ".$documento[6]; ?></td>
						<td><?php echo $documento[9]; ?></td>
						<td><?php echo $documento[2]!="" ? date("d-m-Y H:m", strtotime($documento[2])) : ""; ?></td>
						<td><?php echo $documento[1]; ?></td>
						<td style="text-align:right">
							<a href='ingreso.php?step=4&ID=<?php echo $documento[0] ?>' class='btn btn-default' title="Detalle documento de ingreso">
								<i class='icon-list'></i>
							</a>
							<?php if($documento[4]=="Proceso") { ?>
							<a href='php/ingreso/finalizar.php?&ID=<?php echo $documento[0] ?>' class='btn btn-success' title="Finalizar documento de ingreso">
								<i class='icon-ok icon-white'></i>
							</a>
							<a href='ingreso.php?step=2&ID=<?php echo $documento[0] ?>' class='btn btn-primary' title="Editar documento">
								<i class='icon-edit icon-white'></i>
							</a>
							<a href='php/ingreso/DEL.php?DEL=<?php echo $documento[0] ?>' class='btn btn-danger' title="Eliminar documento">
								<i class='icon-trash icon-white'></i>
							</a>
						 	<?php } ?>
						</td>
					</tr>
			<?php
	    	}
			} ?>
		</tbody>
	</table>
</div>

<?php } // FIN PERMISOS ?>
