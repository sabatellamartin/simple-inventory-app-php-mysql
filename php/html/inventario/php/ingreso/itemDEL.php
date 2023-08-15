<?php session_start();

// ELIMINA EL ITEM DEL MOVIMIENTO
function borraMovimiento($id_documento,$id_detalle) {
  if ($id_detalle > 0 && $id_documento > 0 ) {
  	include "../conexion.php";
    $sql  = "DELETE FROM movimientos WHERE id_documento=$id_documento AND id_detalle=$id_detalle";
   	mysql_query($sql,$conex);
    mysql_close($conex);
  }
}

// ELIMINA EL DETALLE DEL ITEM
function borraDetalle($id_detalle) {
  if ($id_detalle > 0) {
    include "../conexion.php";
  	$sql  = "DELETE FROM detalles_documento WHERE id=$id_detalle";
   	mysql_query($sql,$conex);
    mysql_close($conex);
  }
}

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {
  if (isset($_GET["DEL"]) && isset($_GET["DOC"])) {
    borraMovimiento($_GET["DOC"],$_GET["DEL"]);
    borraDetalle($_GET["DEL"]);
  }
}
header ("Location: ../../ingreso.php?step=2&ID=".$_GET["DOC"]);
?>
