<?php session_start(); ?>

<?php include "php/conexion.php";

$id = $_GET["ID"];
$cant = $_GET["CANT"];?>

		<h3 class="muted">Baja de ítem</h3>

    <div class='form-actions'>
        <form id="frmConfiguracion" class="form-inline" action="php/documento/BAJA.php" method="POST" enctype="multipart/form-data">
          <input class="span1" type="text" placeholder="ID" name="ID" value="<?php echo $id ?>" style="display:none;">
          <select class="span4" name="MOT" title="Seleccione destino de baja" >
            <option value="" selected disabled>-- Seleccione destino de baja --</option>
            <?php
              $sql = "SELECT id, nombre FROM oficinas WHERE id_organismo = 7";

              $result = mysql_query($sql,$conex);
              while ($dir = mysql_fetch_array($result)) {
                $id_of=$dir['id'];
                $nombre_of=$dir['nombre'];
                  if($VROL == $id_org) {
                    echo "<option value='".$id_of."'>".$nombre_of."</option>\n";
                  } else {
                    echo "<option value='".$id_of."'>".$nombre_of."</option>\n";
                  }
              }
            ?>
          </select>
					<?php if ($cant > 1) { ?>
						<input class='span4' type='text' placeholder='Cantidad' name="CANT">
					<?php } ?>
					<br></br>
          <input class='span8' type='text' placeholder='Observación' name="OBS">
          <button  type='submit' class='btn btn-primary'><i class='icon-ok icon-white'></i>&nbsp;Confirmar</button>
        </form>
    </div>
    <div class="tablaEditor"></div>

	<?php	 mysql_close($conex); ?>
