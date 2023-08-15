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

function iCanDelete($id) {
	$delete=false;
  include "../conexion.php";
  $sql="SELECT e.nombre
				FROM documentos AS d
				INNER JOIN estados_documento e ON e.id=d.id_estado
				WHERE d.id=$id";
  $result = mysql_query($sql,$conex);
  while ($row = mysql_fetch_array($result)) {
    if ($row[0]=='Proceso') {
			$delete=true;
		}
  } // end while
	mysql_close($conex);
  return $delete;
}

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {
	if (isset($_GET["DEL"])) {
		if (iCanDelete($_GET["DEL"])) {
			eliminaDocumento($_GET["DEL"]);
  		elimina($_GET["DEL"]);
		}
	}
}
header ("Location: ../../ingreso.php");
?>
