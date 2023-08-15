<?php include "php/conexion.php";

$id_documento = $_GET["ID"];

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

if (isset($_GET["DES"])) {
 $desc = $_GET["DES"];
} else {
 $desc = "";
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
function opcionesFamilia($id) {
	include "php/conexion.php";
	$sql = "SELECT f.id,
								 f.nombre
				  FROM familias AS f
					ORDER BY f.nombre ASC";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
    ?><option value="<?php echo $row[0]; ?>" <?php echo $id==$row[0] ? "selected":""; ?> ><?php echo $row[1]; ?></option> <?php
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

?>

<?php
if (isset($_GET["ART"])) {

  $id_articulo = $_GET["ART"];?>

  <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-content">
      <div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  			<h3 id="myModalLabel">Agregar art&iacute;culo</h3>
  		</div>
      <form class="form-horizontal" action="php/documento/detalles_documentoINS.php?DOC=<?php echo $id_documento; ?>&ART=<?php echo $id_articulo; ?>" method="POST">
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
          <button type="submit" class="btn btn-primary">Agregar</a>
      		<button type="reset" onclick="window.location.href='documento.php?step=2&ID=<?php echo $id_documento ?>'" class="btn">Cancelar</button>
    		</div>
      </form>
    </div>
	</div>

<?php } ?>

<!-- CABEZAL -->
<div class="row">
  <div class="span8">
    <h3 class="muted">Agregar art&iacute;culos al movimiento</h3>
  </div>
  <div class="span3">
    <a class='btn btn-primary' style="margin-top:20px;float:right;" type='submit' href='documento.php?step=2&ID=<?php echo $id_documento ?>' title="Volver"><i class='icon-arrow-left icon-white'></i>&nbsp;&nbsp;Volver</a>
  </div>
</div>

<!-- FORMULARIO -->
<div class="form-actions">
  <form id="form" class="form-inline" action="documento.php" method="GET">
    <fieldset id="frm">
      <div style="margin-left: 80px; padding-left: -10; padding: 10px;">
        <input type="text" name="step" value="5" style="display:none;">
        <input type="text" name="ID" value="<?php echo $id_documento ?>" style="display:none;">
        <select class="span4" name="FAM" title="Seleccione familia">
         <option value="" selected disabled>-- Seleccione familia --</option>
         <?php opcionesFamilia($familia); ?>
        </select>
        <input name='NOM' class='span4' type='text' value='<?php echo $nombre; ?>' placeholder='Nombre'>
        <br></br>
        <input name='DES' class='span4' type='text' value='<?php echo $desc; ?>' placeholder='Descripción'>

        <button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i>&nbsp;&nbsp;Buscar</button>
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
       <th><a href='documento.php?step=5&ORD=f.nombre&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Familia</a></th>
       <th><a href='documento.php?step=5&ORD=a.nombre&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Nombre</a></th>
       <th><a href='documento.php?step=5&ORD=a.descripcion&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>'>Descripci&oacute;n</a></th>
       <th></th>
      </tr>
    </thead>
    <tbody>
    <?php
      $articulos = searchArticulos($familia, $nombre, $desc, $orden);
      include "php/conexion.php";
      if(mysql_num_rows($articulos) > 0) {
        while ($articulo = mysql_fetch_array($articulos)) { ?>
         <tr>
           <td><?php echo $articulo[1]; ?></td>
           <td><?php echo $articulo[2]; ?></td>
           <td><?php echo $articulo[3]; ?></td>
           <td style="text-align:right">
             <a href='documento.php?step=5&ID=<?php echo $id_documento ?>&ART=<?php echo $articulo[0] ?>&ORD=<?php echo $orden; ?>&NOM=<?php echo $nombre; ?>&DES=<?php echo $desc; ?>&FAM=<?php echo $familia; ?>' class='btn btn-info' title="Agregar al documento" type='submit'>
               <i class='icon-plus icon-white'></i>
             </a>
           </td>
        </tr>
        <?php
         }
      }
      mysql_close($conex);
      ?>
    </tbody>
  </table>
</div>
