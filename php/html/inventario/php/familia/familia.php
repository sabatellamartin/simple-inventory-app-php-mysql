<?php

function getFamiliaById($id) {
  include "php/conexion.php";
  $sql="SELECT id, id_familia, nombre, descripcion, codigo FROM familias WHERE id=$id";
  $res = mysql_query($sql,$conex);
  $f = mysql_fetch_array($res);
	mysql_close($conex);
  return $f;
}

// CARGO OPCIONES FAMILIA
function opcionesFamilia($id) {
	include "php/conexion.php";
	$sql = "SELECT f.id,
								 f.nombre,
								 f.codigo
				  FROM familias AS f
					WHERE f.id_familia IS NULL
					ORDER BY f.nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		if($id == $row[0]) {
			?><option value="<?php echo $row[0] ?>" selected><?php echo $row[2]." - ".$row[1] ?></option> <?php
		} else {
			?><option value="<?php echo $row[0] ?>" ><?php echo $row[2]." - ".$row[1] ?></option> <?php
		}
	}
	mysql_close($conex);
}

// CARGO OPCIONES FAMILIA
function getFamilias($OPT, $BUS, $ORD) {
	include "php/conexion.php";
  $sql="SELECT f.id,
               p.nombre,
               f.nombre,
               f.descripcion,
               f.log_update,
               u.usuario,
               COUNT(a.id) as cantidad,
               f.log_insert,
               f.codigo
      FROM familias AS f
      LEFT JOIN articulos a ON a.id_familia = f.id
      LEFT JOIN familias p ON p.id = f.id_familia
      LEFT JOIN usuarios u ON u.id = f.log_usuario
      WHERE (f.nombre LIKE '%".$BUS."%'
      OR f.codigo LIKE '%".$BUS."%'
      OR f.descripcion LIKE '%".$BUS."%') ";
  if($OPT!=""){
    $sql.="AND f.id_familia=$OPT ";
  }
  $sql.="GROUP BY f.id
      ORDER BY ".$ORD." ASC";
  $result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

function messageDelete($id) {
	include "php/conexion.php";
	$sql="SELECT id, nombre FROM familias WHERE id=$id";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
		$id = $row['id'];
		$nombre = $row['nombre'];
	}// fin del while
	mysql_close($conex);
  ?>
  	<!-- Modal -->
  	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  		<div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  			<h3 id="myModalLabel">Eliminar familia/subfamilia</h3>
  		</div>
  		<div class="modal-body">
  			<p>¿Esta seguro de eliminar la familia/subfamilia <?php echo $nombre; ?>?</p>
  		</div>
  		<div class="modal-footer">
  			<a href='familia.php' class="btn" aria-hidden="true">Cancelar</a>
  			<a href='php/familia/DEL.php?DEL=<?php echo $id; ?>' class="btn btn-primary">Eliminar</a>
  		</div>
  	</div>
  <?php
}

$ORD = "f.nombre";
if (isset($_GET["ORD"])) {
   $ORD = $_GET["ORD"];
}
if (isset($_GET["BUS"])) {
	$BUS = $_GET["BUS"];
} else {
	$BUS = "";
}
if (isset($_GET["OPT"])) {
	$OPT = $_GET["OPT"];
} else {
	$OPT = "";
}
?>

<div class="row">
	<div class="col-8">
		<h3 class="muted text-left" style="margin-left:20px;">Familias y subfamilias</h3>
	</div>
	<div class="col-4">
		<?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
    <!--<a class='btn' href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Atr&aacute;s" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Atr&aacute;s</a>-->
    <a class='btn' href="main.php" title="Atr&aacute;s" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Atr&aacute;s</a>
    <a class='btn btn-info' href='familia.php?step=3' title="Agregar subfamilia" style="margin-top:-40px;margin-bottom:10px;float:right;margin-right:90px;"><i class='icon-plus-sign icon-white'></i>&nbsp;&nbsp;Agregar Subfamilia</a>
    <a class='btn btn-primary' href='familia.php?step=1' title="Agregar familia" style="margin-top:-40px;margin-bottom:10px;float:right;margin-right:265px;"><i class='icon-plus-sign icon-white'></i>&nbsp;&nbsp;Agregar Familia</a>
    <?php } ?>
	</div>
</div>

<?php // Delete message
if(isset($_GET["DEL"])) {
  messageDelete($_GET["DEL"]);
} ?>

<?php if($OPT!=""){
  $f = getFamiliaById($OPT); ?>
  <div class="alert alert-info">
    <strong><?php echo $f[2] ?></strong> <?php echo $f[3] ?>
  </div>
<?php }?>

<!-- Jumbotron -->
<div class="form-actions">
  <form id="form" class="form-inline" action="familia.php" method="GET">
		<fieldset id="frm">
		<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
      <!--<select id="comboSubfamilia" class="span6" name="OPT" title="Seleccione padre" onchange="window.location.href='familia.php?BUS=<?php //echo $BUS ?>&OPT='+this.value" disabled> -->
      <input name='BUS' class='span6' type='text' value='<?php echo $BUS; ?>' placeholder='Buscar...'>
      </br></br>
      <?php if($OPT!=""){ ?>
        <div class="input-prepend input-append">
          <label class="checkbox">
            <input id="checkSubfamilia" type="checkbox" onchange="habilitarSubfamilias()" checked> Buscar por subfamilias
          </label>
        </div>
        </br></br>
        <select id="comboSubfamilia" class="span6" name="OPT" title="Seleccione padre" >
      <?php } else { ?>
        <div class="input-prepend input-append">
          <label class="checkbox">
            <input id="checkSubfamilia" type="checkbox" onchange="habilitarSubfamilias()" > Buscar por subfamilias
          </label>
        </div>
        </br></br>
        <select id="comboSubfamilia" class="span6" name="OPT" title="Seleccione padre" disabled>
      <?php } ?>
        <option value="" selected disabled>-- Seleccione familia --</option>
        <?php opcionesFamilia($f[0]); ?>
      </select>
      </br></br>

 		  <button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i>&nbsp;&nbsp;Buscar</button>
      <a class="btn" type="button" href="familia.php" onclick="resetBuscar()" value="Limpiar"><i class="icon-refresh"></i>&nbsp;&nbsp;Limpiar</a>
	 </div>
 	</fieldset>
	</form>
</div>

<hr>

<div class="tablaEditor">
	<table class="table table-striped table-hover">
  	<thead>
	  	<tr>
        <th><a href='familia.php?ORD=f.codigo&BUS=<?php echo $BUS ?>'>C&oacute;digo</a></th>
  			<th><a href='familia.php?ORD=f.nombre&BUS=<?php echo $BUS ?>'><?php if($OPT!=""){ ?>Subfamilia<?php } else { ?>Familia<?php } ?></a></th>
        <th><a href='familia.php?ORD=f.descripcion&BUS=<?php echo $BUS ?>'>Descripci&oacute;n</a></th>
        <!--<th><a href='familia.php?ORD=p.nombre&BUS=<?php //echo $BUS ?>'>Padre</a></th>-->
        <th><a href='familia.php?ORD=cantidad&BUS=<?php echo $BUS ?>'>Cant. art&iacute;culos</a></th>
        <th><a href='familia.php?ORD=f.log_insert,f.log_update&BUS=<?php echo $BUS ?>'>Actualizado</a></th>
        <th><a href='familia.php?ORD=u.nombre&BUS=<?php echo $BUS ?>'>Usuario</a></th>
        <th></th>
       </tr>
     </thead>
		 <tbody>
			<?php
      $result = getFamilias($OPT, $BUS, $ORD);
	    if(mysql_num_rows($result) != 0) {
    		while ($reg = mysql_fetch_array($result)) { ?>
    			<tr>
            <td><?php echo $reg[8] ?></td>
						<td><?php echo $reg[2] ?></td>
					 	<td><?php echo $reg[3] ?></td>
            <!--<td><?php //echo $reg[1] ?></td>-->
            <td><?php echo $reg[6] ?></td>
            <td><?php echo $reg[4]!="" ? date("d-m-Y H:m", strtotime($reg[4])) : date("d-m-Y H:m", strtotime($reg[7])) ?></td>
            <td><?php echo $reg[5] ?></td>
						<td style="text-align:right">
							<?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
              <a href='familia.php?step=2&ID=<?php echo $reg[0] ?>' class='btn btn-primary' title="Modificar" type='submit'>
								<i class='icon-edit icon-white'></i>
							</a>
                <?php if($reg[6]==0) { ?>
                <!--<a href='php/familia/DEL.php?DEL=<?php echo $reg[0] ?>' class='btn btn-danger' title="Eliminar familia" type='submit'>-->
                <a href='familia.php?DEL=<?php echo $reg[0] ?>' class='btn btn-danger' title="Eliminar familia" type='submit'>
  								<i class='icon-trash icon-white'></i>
  							</a>
					 		  <?php } ?>
					 		<?php } ?>
						</td>
					</tr>
        <?php }
			} ?>
		</tbody>
	</table>
</div>
