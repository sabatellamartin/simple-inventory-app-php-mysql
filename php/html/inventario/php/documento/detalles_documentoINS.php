<?php session_start();

// OBTENGO EL MAX ITEM ID PARA INSERTARLO EN LA TABLA DE MOVIMIENTOS
function guardaMovimiento($id_documento) {
	include "../conexion.php";
  $id_detalle = 0;
	$sql = "SELECT MAX(dd.id) FROM detalles_documento AS dd";
	$result = mysql_query($sql,$conex);
  while ($row = mysql_fetch_array($result)) {
	   $id_detalle = $row[0];
	}
  if ($id_detalle > 0 && $id_documento > 0 ) {
    $sql  = "INSERT INTO movimientos (id_documento,id_detalle,fecha)
             VALUES ($id_documento, $id_detalle, '".date("Y-m-d H:i:s")."')";
    $result = mysql_query($sql,$conex);
  }
  mysql_close($conex);
}


if($_SESSION['tipo'] == "administrador" || $_SESSION['tipo']=='operador inventario') {

   include "../conexion.php";

  $id_documento = $_GET["DOC"];
  $id_articulo = $_GET["ART"];
	$cantidad = $_POST["CANT"] > 0 ? $_POST["CANT"] : 1;
	$observacion = $_POST["OBS"];

  $codigo = $_POST["COD"];
  $id_tipo_procedimiento = $_POST["PRO"];
  $nro_procedimiento = $_POST["NRO"];
  $id_adjudicatario = $_POST["ADJ"];
  $nro_factura = $_POST["FAC"];
  $fecha_factura = $_POST["FEC"];
  $plazo_garantia = $_POST["PLA"];

	$log_insert = date("Y-m-d H:i:s");
  $log_usuario = $_SESSION['id_usuario'];
  // Si tiene numero de procedimineto y adjudicatario
  if ($id_tipo_procedimiento !="" && $id_adjudicatario !="") {
  	$sql  = "INSERT INTO detalles_documento (id_articulo,
                                             cantidad,
                                             observacion,
                                             codigo,
                                             id_tipo_procedimiento,
                                             numero_procedimiento,
                                             id_adjudicatario,
                                             numero_factura,
                                             fecha_factura,
                                             plazo_garantia,
                                             log_usuario,
                                             log_insert)
                                     VALUES ('".$id_articulo."',
                                             '".$cantidad."',
                                             '".$observacion."',
                                             '".$codigo."',
                                             $id_tipo_procedimiento,
                                             '".$nro_procedimiento."',
                                             $id_adjudicatario,
                                             '".$nro_factura."',
                                             '".$fecha_factura."',
                                             '".$plazo_garantia."',
                                             '".$log_usuario."',
                                             '".$log_insert."')";
  } else {
    $sql  = "INSERT INTO detalles_documento (id_articulo,
                                             cantidad,
                                             observacion,
                                             codigo,
                                             log_usuario,
                                             log_insert)
                                     VALUES ('".$id_articulo."',
                                             '".$cantidad."',
                                             '".$observacion."',
                                             '".$codigo."',
                                             '".$log_usuario."',
                                             '".$log_insert."')";

  }

	$result = mysql_query($sql,$conex);
	mysql_close($conex);

  // GUARDO EL REGISTRO DE MOVIMIENTO DEL DOCUMENTO Y EL ITEM
  guardaMovimiento($id_documento);
}
$referencia = $_SERVER['HTTP_REFERER'];
header ("Location: ../../documento.php?step=2&ID=".$id_documento."");
?>
