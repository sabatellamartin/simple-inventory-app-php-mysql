<?php if ($_SESSION['tipo']=='operador') { ?>

<?php
// OBTENGO EL DETALLE DEL DOCUMENTO
function getDetalle() {
	include "php/conexion.php";
	/*$sql = "SELECT d.id,
                 d.observacion,
                 d.cantidad,
								 d.fecha_baja,
                 a.fecha_baja,
								 a.nombre,
								 a.descripcion,
                 f.nombre,
								 f.descripcion,
								 m.id_organismo_receptor,
								 o.nombre
          FROM detalles_documento AS d
					INNER JOIN articulos a ON a.id = d.id_articulo
          INNER JOIN familias f ON f.id = a.id_familia
					INNER JOIN movimientos m ON m.id_detalle = d.id
					INNER JOIN documentos dd ON dd.id = m.id_documento
					INNER JOIN organismos o ON o.id = m.id_organismo_receptor
					WHERE d.fecha_baja IS NULL";*/
					$sql = "SELECT a.id,
				                 f.nombre,
												 f.descripcion,
				                 a.nombre,
				                 a.descripcion,
				                 dd.id,
				                 dd.cantidad,
				                 dd.codigo,
				                 dd.observacion,
												 dd.fecha_baja,
				                 m.fecha,
				                 orge.nombre,
												 orgr.id,
				                 orgr.nombre
				          FROM detalles_documento AS dd
				          INNER JOIN articulos a ON a.id = dd.id_articulo
				          INNER JOIN familias f ON f.id = a.id_familia
				          INNER JOIN movimientos m ON m.id_detalle = dd.id
				          INNER JOIN documentos d ON d.id = m.id_documento
				          INNER JOIN organismos orge ON orge.id = d.id_organismo_emisor
				          INNER JOIN organismos orgr ON orgr.id = d.id_organismo_receptor
				          WHERE (dd.fecha_baja IS NULL)";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

?>

<div class="row">
  <div class="col-md-8">
    <h3 class="muted text-left">Art&iacute;culos DEP&Oacute;SITO</h3>
  </div>
  <!-- <div class="col-md-4 no-print">
    <button id="printbutton" onclick="window.print();" class="btn btn-info" type="button" style="margin-top:-50px;margin-bottom:20px;float:right;">
      Imprimir <i class="icon-print icon-white"></i>
    </button>
  </div> -->
</div>

<?php
// MODIFICAR LA SALIDA DE DETALLE PARA OBTENER EL REPORTE DESEADO
//var_dump($detalle); ?>

<div class="row" media="print" >
  <div class="tablaEditor">
    <table class="table table-striped">
			<?php
			$detalle = getDetalle();
			if(mysql_num_rows($detalle) > 0) { ?>
	      <thead>
	        <tr>
	          <th>#</th>
	          <th>Art&iacute;culo</th>
	          <th>Descripci&oacute;n</th>
	          <th>Familia</th>
	          <th>Cantidad</th>
	          <th>C&oacute;digo</th>
	          <th>Observaci&oacute;n</th>
						<!--<th>Organismo</th>-->
	          <th></th>
	        </tr>
	      </thead>
	      <tbody>
        <?php

          while ($linea = mysql_fetch_array($detalle)) {
						if ($linea[12] == 7) {?>
            <tr>
              <td><?php echo $linea[5]; ?></td>
              <td><?php echo $linea[3]; ?></td>
              <td><?php echo $linea[4]; ?></td>
              <td><?php echo $linea[2]; ?></td>
              <td><?php echo $linea[6]; ?></td>
              <td><?php echo $linea[7]; ?></td>
              <td><?php echo $linea[8]; ?></td>
              <td style="text-align:right">
              <?php if(($_SESSION['tipo']=='operador')) { ?>
							<a href='baja.php?step=1&ID=<?php echo $linea[5] ?>&CANT=<?php echo $linea[6] ?>' class='btn btn-danger' title="Baja de ítem" type='submit'>
								<i class='icon-arrow-down icon-white'></i>
							</a>
					 		<?php } ?>
              </td>
            </tr> <?php
          }
				}
        }
        ?>
      </tbody>
    </table>
  </div>
</div>


<?php } ?>
