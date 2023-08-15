<?php include "php/conexion.php";

$id_documento = $_GET["ID"];

$sql="SELECT id, id_tipo_documento, id_estado, id_organismo_emisor, id_usuario_emisor, id_organismo_receptor, observacion FROM documentos WHERE id=$id_documento";

 $res = mysql_query($sql,$conex);

 if(mysql_num_rows($res) != 0) {

	while ($doc = mysql_fetch_array($res)) {
		$id = $doc['id'];
		$tipo = $doc['id_tipo_documento'];
		$estado = $doc['id_estado'];
		$orgE = $doc['id_organismo_emisor'];
		$usuE = $doc['id_usuario_emisor'];
		$orgR = $doc['id_organismo_receptor'];
		$obs = $doc['observacion'];
	}// fin del while
}// fin del if

?>

<h3 class="muted">Actualizar movimiento</h3>


	<div class='form-actions'>
			<form id="frmConfiguracion" class="form-inline" action="php/documento/UPD.php" method="POST" enctype="multipart/form-data">
				<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
					<input class="span1" type="text" placeholder="ID" name="ID" value="<?php echo $id ?>" style="display:none;">
					<select class="span4" name="TIPO" title="Seleccione tipo" disabled>
						<option value="" selected disabled>-- Seleccione tipo --</option>
						<?php
							// CARGO EL COMBO TIPOS
							$sql = "SELECT id, descripcion FROM tipos_documento";
							$result = mysql_query($sql,$conex);
							while ($dir = mysql_fetch_array($result)) {
								$id_tipo=$dir['id'];
								$desc_tipo=$dir['descripcion'];
									if($tipo == $id_tipo) {
										echo "<option value='".$id_tipo."' selected>".$desc_tipo."</option>\n";
									} else {
										echo "<option value='".$id_tipo."'>".$desc_tipo."</option>\n";
									}
							} // end while
						?>
					</select>
					<select class="span4" name="EST" title="Seleccione estado" disabled>
						<option value="" selected disabled>-- Seleccione estado --</option>
						<?php
							// CARGO EL COMBO ESTADOS
							$sql = "SELECT id, nombre FROM estados_documento";
							$result = mysql_query($sql,$conex);
							while ($dir = mysql_fetch_array($result)) {
								$id_est=$dir['id'];
								$nombre_est=$dir['nombre'];
									if($estado == $id_est) {
										echo "<option value='".$id_est."' selected>".$nombre_est."</option>\n";
									} else {
										echo "<option value='".$id_est."'>".$nombre_est."</option>\n";
									}
							} // end while
						?>
					</select>
					<br></br>
          <select class="span4" name="ORGE" title="Seleccione unidad emisora" >
            <option value="" selected disabled>-- Seleccione unidad emisora --</option>
            <?php
              // CARGO EL COMBO ORGANISMO EMISOR
              $id_usuario = $_SESSION['id_usuario'];
              $sql = "SELECT id_organismo FROM usuarios_organismos WHERE id_usuario='".$id_usuario."'";

              $result = mysql_query($sql,$conex);
              while ($dir = mysql_fetch_array($result)) {
                $id_org=$dir['id_organismo'];
                $sql2 = "SELECT nombre FROM organismos WHERE id='".$id_org."'";
                $result2 = mysql_query($sql2, $conex);
                $dir2=mysql_fetch_array($result2);
                $nombre_org=$dir2['nombre'];
                  if($orgE == $id_org) {
                    echo "<option value='".$id_org."' selected>".$nombre_org."</option>\n";
                  } else {
                    echo "<option value='".$id_org."'>".$nombre_org."</option>\n";
                  }
              } // end while
            ?>
          </select>
          <?php //if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario' || $_SESSION['tipo']=='operador') { ?>
					<select class="span4" name="ORG" title="Seleccione unidad receptora" disabled>
						<option value="" selected disabled>-- Seleccione unidad receptora --</option>
						<?php
							// CARGO EL COMBO ORGANISMO RECEPTOR
							$sql = "SELECT id, nombre FROM organismos";
							$result = mysql_query($sql,$conex);
							while ($dir = mysql_fetch_array($result)) {
								$id_org=$dir['id'];
								$nombre_org=$dir['nombre'];
									if($orgR == $id_org) {
										echo "<option value='".$id_org."' selected>".$nombre_org."</option>\n";
									} else {
										echo "<option value='".$id_org."'>".$nombre_org."</option>\n";
									}
							} // end while
						?>
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

      <a href='documento.php?step=5&ID=<?php echo $id ?>' class='btn btn-info' title="Agregar artículos">
      	<i class='icon-plus icon-white'></i>&nbsp;Agregar art&iacute;culos
  		</a>


    </div>

    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th><a href='#'>Cantidad</a></th>
          <th><a href='#'>Nombre</a></th>
          <th><a href='#'>Descripción</a></th>
          <th></th>
         </tr>
       </thead>
       <tbody>
    <?php
        $sql="SELECT dd.id, dd.id_articulo, dd.cantidad FROM detalles_documento AS dd
              INNER JOIN movimientos m ON m.id_detalle = dd.id
              WHERE m.id_documento = '$id_documento'";

        $result = mysql_query($sql,$conex);

        if(mysql_num_rows($result) != 0) {
          while ($reg = mysql_fetch_array($result)) {
            $id = $reg['id'];
            $id_art = $reg['id_articulo'];
            $cant = $reg['cantidad'];
            $sql="SELECT nombre, descripcion FROM articulos WHERE id = '$id_art'";
            $res = mysql_query($sql,$conex);
            $fila = mysql_fetch_row($res);
            $nom = $fila[0];
            $des = $fila[1];
            ?>

            <tr>
              <td><?php echo $cant; ?></td>
              <td><?php echo $nom; ?></td>
              <td><?php echo $des; ?></td>
              <td style="text-align:right">
                <a href='documento.php?step=6&ID=<?php echo $id ?>' class='btn btn-default' title="Ver informaci&oacute;n del &iacute;tem" type='submit'>
  								<i class='icon-eye-open'></i>
  							</a>
                <a href='php/documento/detalles_documentoDEL.php?DOC=<?php echo $id_documento ?>&DEL=<?php echo $id ?>' class='btn btn-danger' title="Eliminar artículo" type='submit'>
                  <i class='icon-remove icon-white'></i>
                </a>
              </td>
           </tr>

           <?php
            }
          } ?>
      </tbody>
    </table>
	</div>

<?php	 mysql_close($conex); ?>
