<?php session_start();

// Elimino en cascada el movimiento del documento y los detalles asociados a este
function elimina($id_documento) {
	include "../conexion.php";
	$sql = "SELECT m.id,m.id_detalle
					FROM movimientos m
					WHERE m.id_documento = $id_documento";
	$result = mysql_query($sql,$conex);
	while ($row = mysql_fetch_array($result)) {
	   eliminaMovimiento($row[0]);
     eliminaDetalle($row[1]);
	} // End while
	mysql_close($conex);
}

function eliminaDocumento($id_documento) {
  if ($id_documento > 0) {
	   include "../conexion.php";
     $sql  = "DELETE FROM documentos WHERE id=$id_documento";
 	   mysql_query($sql,$conex);
     mysql_close($conex);
  }
}

function eliminaMovimiento($id_movimiento) {
  if ($id_movimiento > 0) {
	   include "../conexion.php";
     $sql  = "DELETE FROM movimientos WHERE id=$id_movimiento";
 	   mysql_query($sql,$conex);
  }
}

function eliminaDetalle($id_detalle) {
  if ($id_detalle > 0) {
	   include "../conexion.php";
     $sql  = "DELETE FROM detalles_documento WHERE id=$id_detalle";
 	   mysql_query($sql,$conex);
	}
}

if($_SESSION['tipo'] == "administrador") {
	if (isset($_GET["DEL"])) {
		$id_documento = $_GET["DEL"];
	}
  elimina($id_documento);
	eliminaDocumento($id_documento);
}
header ("Location: ../../documento.php");
?>
