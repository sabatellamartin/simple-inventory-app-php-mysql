<?php

function getFamiliaById($id) {
  include "php/conexion.php";
  $sql="SELECT id, id_familia, nombre, descripcion, codigo FROM familias WHERE id=$id";
  $res = mysql_query($sql,$conex);
  $f = mysql_fetch_array($res);
	mysql_close($conex);
  return $f;
}

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
function opcionesFamilia($familia) {
	include "php/conexion.php";
	$sql = "SELECT f.id,
								 f.nombre,
                 f.codigo
				  FROM familias AS f
          WHERE f.id_familia IS NULL
					ORDER BY f.nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
	   ?><option value="<?php echo $row[0] ?>" <?php echo $familia==$row[0] ? "selected":""; ?> ><?php echo $row[2]." - ".$row[1] ?></option> <?php
	}
	mysql_close($conex);
}

function opcionesSubfamilia($familia, $subfamilia) {
	include "php/conexion.php";
	$sql = "SELECT f.id,
								 f.nombre,
                 f.codigo
				  FROM familias AS f
          WHERE f.id_familia IS NOT NULL
          AND f.id_familia = $familia
					ORDER BY f.nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
	   ?><option value="<?php echo $row[0] ?>"  <?php echo $subfamilia==$row[0] ? "selected":""; ?> ><?php echo $row[2]." - ".$row[1] ?></option> <?php
	}
	mysql_close($conex);
}

// SEARCH
function searchArticulos($id_familia, $nombre, $descripcion, $orden) {
	include "php/conexion.php";
  $sql = "SELECT a.id,
                 f.nombre,
                 a.nombre,
                 a.descripcion
          FROM articulos AS a
          INNER JOIN familias f ON f.id = a.id_familia
          WHERE (a.fecha_baja IS NULL)
          AND a.nombre LIKE '%".$nombre."%'
          AND a.descripcion LIKE '%".$descripcion."%'
          AND a.id_familia LIKE '%".$id_familia."%'
          ORDER BY $orden ASC";
	$result = mysql_query($sql,$conex);
	mysql_close($conex);
  return $result;
}

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
if (isset($_GET["SFAM"])) {
	$subfamilia = $_GET["SFAM"];
} else {
	$subfamilia = "";
}
if (isset($_GET["DES"])) {
 $descripcion = $_GET["DES"];
} else {
 $descripcion = "";
}
?>

<!-- CABEZAL -->
<div class="row">
	<div class="col-8">
		<h3 class="muted text-left" style="margin-left:20px;">Busqueda de art&iacute;culos para el documento Nro. <?php echo $_GET["ID"] ?></h3>
	</div>
	<div class="col-4">
		<a class='btn' href="ingreso.php?step=2&ID=<?php echo $_GET["ID"] ?>" title="Volver" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Volver</a>
	</div>
</div>

<div class="alert alert-info">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Buqueda de artículos:</strong> Busque entre los articulos disponibles a través de los filtros y agregelos con "+" para crear items en el documento.
</div>

<?php if (isset($_GET["FAM"]) && $_GET["FAM"]!=""){
	$f = getFamiliaById($_GET["FAM"]); ?>
	<div class="alert alert-success">
  	<strong><?php echo $f[2] ?></strong> <?php echo $f[3] ?>
	</div>
<?php }?>

<?php if (isset($_GET["SFAM"]) && $_GET["SFAM"]!=""){
	$sf = getFamiliaById($_GET["SFAM"]); ?>
	<div class="alert alert-success">
  	<strong><?php echo $sf[2] ?></strong> <?php echo $sf[3] ?>
	</div>
<?php }?>

<!-- FORMULARIO -->
<div class="form-actions">
  <form id="form" class="form-inline" action="ingreso.php" method="GET">
    <fieldset id="frm">
      <div style="margin-left: 80px; padding-left: -10; padding: 10px;">
        <input type="text" name="step" value="3" style="display:none;">
        <input type="text" name="ID" value="<?php echo $_GET["ID"] ?>" style="display:none;">

        <select class="span4" name="FAM" title="Busqueda por subfamilia"
          onchange="window.location.href='ingreso.php?step=3&ID=<?php echo $_GET["ID"] ?>&NOM=<?php echo $_GET["NOM"] ?>&DES=<?php echo $_GET["DES"] ?>&FAM='+this.value" >
    			<option value="" selected disabled>-- Busqueda por familia --</option>
          <?php opcionesFamilia($_GET["FAM"]); ?>
    		</select>
        <select class="span4" name="SFAM" title="Busqueda por subfamilia"
          onchange="window.location.href='ingreso.php?step=3&ID=<?php echo $_GET["ID"] ?>&NOM=<?php echo $_GET["NOM"] ?>&DES=<?php echo $_GET["DES"] ?>&FAM=<?php echo $_GET["FAM"] ?>&SFAM='+this.value" >
          <option value="" selected disabled>-- Busqueda por subfamilia --</option>
          <?php opcionesSubfamilia($_GET["FAM"], $_GET["SFAM"]); ?>
        </select>
        <br></br>
    		<input name='NOM' class='span4' type='text' value='<?php echo $_GET["NOM"] ?>' placeholder='Filtro por nombre'>
    		<input name='DES' class='span4' type='text' value='<?php echo $_GET["DES"] ?>' placeholder='Filtro por descripción'>
        <br></br>
        <a class="btn" href="ingreso.php?step=3&ID=<?php echo $_GET["ID"] ?>" type="button" onclick="resetBuscar()" value="Limpiar"><i class="icon-refresh"></i>&nbsp;&nbsp;Limpiar</a>
        <button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i>&nbsp;&nbsp;Buscar artículos</button>
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
       <th><a href='ingreso.php?step=3&ID=<?php echo $_GET["ID"] ?>&ORD=f.nombre&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Familia</a></th>
       <th><a href='ingreso.php?step=3&ID=<?php echo $_GET["ID"] ?>&ORD=a.nombre&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Nombre</a></th>
       <th><a href='ingreso.php?step=3&ID=<?php echo $_GET["ID"] ?>&ORD=a.descripcion&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Descripci&oacute;n</a></th>
       <th></th>
      </tr>
    </thead>
    <tbody>
    <?php
      $articulos = searchArticulos($familia, $nombre, $descripcion, $orden);
      if(mysql_num_rows($articulos) > 0) {
        while ($articulo = mysql_fetch_array($articulos)) { ?>
         <tr>
           <td><?php echo $articulo[1]; ?></td>
           <td><?php echo $articulo[2]; ?></td>
           <td><?php echo $articulo[3]; ?></td>
           <td style="text-align:right">
             <a href='ingreso.php?step=3&ID=<?php echo $_GET["ID"] ?>&ART=<?php echo $articulo[0] ?>&ORD=<?php echo $orden; ?>&NOM=<?php echo $nombre; ?>&DES=<?php echo $descripcion; ?>&FAM=<?php echo $familia ?>&SFAM=<?php echo $subfamilia ?>'
               class='btn btn-info' title="Agregar al documento" type='submit'>
               <i class='icon-plus icon-white'></i>
             </a>
           </td>
        </tr>
      <?php } // FIN WHILE ?>
      <?php } // FIN IF ?>
    </tbody>
  </table>
</div>


<?php if (isset($_GET["ART"])) { ?>

  <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-content">
      <div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  			<h3 id="myModalLabel">Crear item de inventario</h3>
  		</div>
      <form class="form-horizontal" action="php/ingreso/itemINS.php?DOC=<?php echo $_GET["ID"] ?>&ART=<?php echo $_GET["ART"] ?>" method="POST">
		    <div class="modal-body">
          <div class="row" style="margin-top:20px;margin-left:20px;">
            <div class="input-prepend input-append">
        			<span class="add-on"><i class="icon-plus-sign"></i></span>
        			<input id="cantidadItem" class="span6" name="CANT" type="number" placeholder="Cantidad" min="1" max="999999" onblur="habilitarCodigo()">
        		</div>
            <br></br>
            <div class="input-prepend input-append">
        			<span class="add-on"><i class="icon-barcode"></i></span>
        			<input id="codigoItem" class="span6" name="COD" type="text" placeholder="Código" disabled>
        		</div>
            <br></br>
            <div class="input-prepend input-append">
        			<span class="add-on"><i class="icon-eye-open"></i></span>
        			<input class="span6" name="OBS" type="text" placeholder="Observación">
        		</div>
            <br></br>
            <div class="input-prepend input-append">
              <label class="checkbox">
                <input id="checkProcedimiento" type="checkbox" onchange="habilitarProcedimiento()"> Establecer procedimiento
              </label>
            </div>
            <br></br>
            <div class="input-prepend input-append">
              <span class="add-on"><i class="icon-file"></i></span>
              <select id="tipoProcedimiento" class="span4" name="PRO" title="Seleccione tipo de procemidmiento" disabled>
                <option value="" selected>-- Seleccione tipo de procemidmiento --</option>
                <?php opcionesProcedimiento(); ?>
              </select>
              <span class="add-on"><i class="icon-asterisk"></i></span>
              <input id="nroProcedimiento" class="span2" name="NRO" type="number" min='1' placeholder="Nro. de procedimiento" disabled>
            </div>
            <br></br>
            <div class="input-prepend input-append">
              <span class="add-on"><i class="icon-home"></i></span>
          		<select id="adjudicatarioProcedimiento" class="span4" name="ADJ" title="Seleccione adjudicatario" disabled>
                <option value="" selected>-- Seleccione adjudicatario --</option>
                <?php opcionesAdjudicatario(); ?>
              </select>
              <span class="add-on"><i class="icon-asterisk"></i></span>
              <input id="nroFacturaProcedimiento" class="span2" name="FAC" type="text" placeholder="Nro. de factura" disabled>
            </div>
            <br></br>
            <div class="input-prepend input-append">
              <span class="add-on"><i class="icon-calendar"></i></span>
              <input id="garantiaProcedimiento" class="span3" name="FEC" type="date" placeholder="Fecha de compra" disabled>
              <span class="add-on"><i class="icon-time"></i></span>
              <input id="plazoProcedimiento" class="span3" name="PLA" type="number" min='0' placeholder="Plazo de garant&iacute;a (d&iacute;as)" disabled>
            </div>
          </div>
        </div>
    		<div class="modal-footer">
          <button type="submit" class="btn btn-info"><i class='icon-plus icon-white'></i> Agregar item</a>
      		<button type="reset"
            onclick="window.location.href='ingreso.php?step=3&ID=<?php echo $_GET["ID"] ?>&ORD=<?php echo $orden; ?>&NOM=<?php echo $nombre; ?>&DES=<?php echo $descripcion; ?>&FAM=<?php echo $familia ?>&SFAM=<?php echo $subfamilia ?>'"
            class="btn">Cancelar</button>
    		</div>
      </form>
    </div>
	</div>
<?php } ?>
