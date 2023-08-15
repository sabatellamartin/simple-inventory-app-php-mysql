<?php

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {

  if (isset($_GET["ID"])) {
    $id = $_GET["ID"];
  } else {
  	$id = "";
  }

  // OBTENGO EL ITEM
  function getItem($id) {
  	include "php/conexion.php";

    $sql = "SELECT dd.id,
                   a.nombre,
                   a.descripcion,
                   f.nombre,
                   dd.cantidad,
                   dd.codigo,
                   dd.observacion,
                   t.descripcion,
                   dd.numero_procedimiento,
                   ad.nombre,
                   ad.descripcion,
                   ad.rut,
                   ad.telefono,
                   ad.contacto,
                   ad.email,
                   dd.numero_factura,
                   dd.fecha_factura,
                   dd.plazo_garantia,
                   u.nombre,
                   u.apellido,
                   u.email,
                   dd.log_insert
            FROM detalles_documento AS dd
            LEFT JOIN usuarios u ON u.id = dd.log_usuario
            LEFT JOIN articulos a ON a.id = dd.id_articulo
            LEFT JOIN familias f ON f.id = a.id_familia
            LEFT JOIN tipos_procedimiento t ON t.id = dd.id_tipo_procedimiento
            LEFT JOIN adjudicatarios ad ON ad.id = dd.id_adjudicatario
            WHERE dd.id = $id";

  	$result = mysql_query($sql,$conex);
  	if(mysql_num_rows($result) != 0) {
  		$reg = mysql_fetch_row($result);
  		/*echo $reg[0];*/
  	}
  	mysql_close($conex);
    return $reg;
  }

  $item = getItem($id);
  ?>

  <div class="row">
    <div class="col-md-8">
      <h3 class="muted text-left">Informaci&oacute;n del &iacute;tem Nro <?php echo $item[0]; ?></h3>
    </div>
    <div class="col-md-4 no-print">
      <a href='documento.php?step=9&ID=<?php echo $id ?>' class='btn btn-primary' title="Modificar procedimiento del &iacute;tem" type='submit'
        style="margin-top:-50px;margin-bottom:20px;margin-right:100px;float:right;" >
        Procedimiento&nbsp;<i class='icon-pencil icon-white'></i>
      </a>
      <button id="printbutton" onclick="window.print();" class="btn btn-info" type="button" style="margin-top:-50px;margin-bottom:20px;float:right;">
        Imprimir <i class="icon-print icon-white"></i>
      </button>
    </div>
  </div>

  <div class="row" media="print" >
    <div class="col-md-12">
      <table class="table table-bordered">
        <thead>
        </thead>
        <tbody>
          <tr>
            <th>#</th>
            <td><?php echo $item[0]; ?></td>
          </tr>
          <tr>
            <th>Nro. Documento</th>
            <td><?php echo $item[1]; ?></td>
          </tr>
          <tr>
            <th>Nombre art&iacute;culo</th>
            <td><?php echo $item[2]; ?></td>
          </tr>
          <tr>
            <th>Descripci&oacute;n art&iacute;culo</th>
            <td><?php echo $item[3]; ?></td>
          </tr>
          <tr>
            <th>Familia art&iacute;culo</th>
            <td><?php echo $item[4]; ?></td>
          </tr>
          <tr>
            <th>Cantidad</th>
            <td><?php echo $item[5]; ?></td>
          </tr>
          <tr>
            <th>C&oacute;digo</th>
            <td><?php echo $item[6]; ?></td>
          </tr>
          <tr>
            <th>Observaci&oacute;n</th>
            <td><?php echo $item[7]; ?></td>
          </tr>
          <tr>
            <th>Tipo de procedimiento</th>
            <td><?php echo $item[8]; ?></td>
          </tr>
          <tr>
            <th>N&uacute;mero de procedimiento</th>
            <td><?php echo $item[9]; ?></td>
          </tr>
          <tr>
            <th>Nombre adjudicatario</th>
            <td><?php echo $item[10]; ?></td>
          </tr>
          <tr>
            <th>Descripci&oacute;n adjudicatario</th>
            <td><?php echo $item[11]; ?></td>
          </tr>
          <tr>
            <th>RUT adjudicatario</th>
            <td><?php echo $item[12]; ?></td>
          </tr>
          <tr>
            <th>Tel&eacute;fono adjudicatario</th>
            <td><?php echo $item[13]; ?></td>
          </tr>
          <tr>
            <th>Contacto adjudicatario</th>
            <td><?php echo $item[14]; ?></td>
          </tr>
          <tr>
            <th>Email adjudicatario</th>
            <td><?php echo $item[15]; ?></td>
          </tr>
          <tr>
            <th>N&uacute;mero de factura</th>
            <td><?php echo $item[16]; ?></td>
          </tr>
          <tr>
            <th>Fecha de emisi&oacute;n factura</th>
            <td><?php echo $item[17]!="" ? date("d-m-Y H:m", strtotime($item[17])) : ""; ?></td>
          </tr>
          <tr>
            <th>Plazo de garant&iacute;a en d&iacute;as</th>
            <td><?php echo $item[18]; ?></td>
          </tr>
          <tr>
            <th>Usuario de ingreso</th>
            <td><?php echo $item[19]." ".$item[20]."; ".$item[21]; ?></td>
          </tr>
          <tr>
            <th>Fecha de ingreso</th>
            <td><?php echo $item[22]!="" ? date("d-m-Y H:m", strtotime($item[22])) : ""; ?></td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>

<?php } ?>
