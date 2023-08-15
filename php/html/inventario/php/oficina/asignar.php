<?php

//if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {

  if (isset($_GET["ID"])) {
    $ID = $_GET["ID"];
  } else {
  	$ID = "";
  }

  function opcionesOficina($item) {
  	include "php/conexion.php";
    $sql = "SELECT o.id,
                   orgr.sigla AS receptor,
                   o.nombre AS oficina,
                   dd.id_oficina
  					FROM
  					( SELECT m.id,m.id_documento,m.id_detalle,m.observacion, MAX(m.fecha) AS fecha
  					  FROM movimientos AS m
  					  GROUP BY m.id_detalle
  					) AS m
  					INNER JOIN documentos d ON d.id = m.id_documento
  					INNER JOIN organismos orgr ON orgr.id = d.id_organismo_receptor
  					LEFT JOIN oficinas o ON o.id_organismo = orgr.id
            INNER JOIN detalles_documento dd ON dd.id = m.id_detalle
  					WHERE d.id_estado = 6
  					AND m.id_detalle = $item";
  	$result = mysql_query($sql,$conex);
  	while ($row = mysql_fetch_array($result)) {
  		if($row[3] == $row[0]) {
  			?><option value="<?php echo $row[0]; ?>" selected><?php echo $row[1]." - ".$row[2] ?></option> <?php
  		} else {
  			?><option value="<?php echo $row[0]; ?>" ><?php echo $row[1]." - ".$row[2] ?></option> <?php
  		}
  	} // End while
  	mysql_close($conex);
  }

  function getItemById($item_id) {
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
  					WHERE d.id_estado = 6
  					AND dd.id = $item_id";
  	$result = mysql_query($sql,$conex);
  	mysql_close($conex);
    return mysql_fetch_array($result);
  }

$item = getItemById($ID);
?>

  <div class="row">
  	<div class="col-8">
  		<h3 class="muted text-left" style="margin-left:20px;">Asignar oficina</h3>
  	</div>
  	<div class="col-4">
  		<a class='btn no-print' href="oficina.php?step=1" title="Atr&aacute;s" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Distribuir items</a>
  	</div>
  </div>

<hr>
  <div>
   <table class="table table-striped table-hover">
     <thead>
       <tr>
         <th>Familia&nbsp;</th>
         <th>Art&iacute;culo&nbsp;</th>
         <th>Cantidad&nbsp;</th>
         <th>Oficina&nbsp;</th>
         <th>Ubicaci&oacute;n actual</th>
         <th>Último movimiento&nbsp;</th>
        </tr>
      </thead>
      <tbody>
             <tr class="info">
             <td><?php echo $item[4] ?></td>
             <td><?php echo $item[5] ?></td>
             <td><?php echo $item[6] ?></td>
             <td><?php echo $item[0]!="" ? $item[0] : "-" ?></td>
             <td><?php echo $item[3] ?></td>
             <td><?php echo $item[1]!="" ? date("d-m-Y H:m", strtotime($item[1])) : "" ?></td>
           </tr>

     </tbody>
   </table>
 </div>

  <div class="form-actions">
   <form class="form-horizontal" id="frmTipo" action="php/oficina/asignarUPD.php" method="POST" enctype="multipart/form-data">

       <input class="span1" name="ID" style="display:none;" type="text" value="<?php echo $ID ?>">

       <div  id="input-edificio" class="input-prepend input-append">
         <span class="add-on"><i class='icon-chevron-down'></i></span>
         <select class="span4" name="OFI" title="Seleccione oficina">
           <option value="" selected>-- Seleccione oficina --</option>
           <?php opcionesOficina($ID); ?>
         </select>
       </div>

       <button type="submit" style="margin-top:0px;margin-right:5px;" class="btn btn-primary">Asignar</a>
   </form>
   </div>


<?php //} ?>
