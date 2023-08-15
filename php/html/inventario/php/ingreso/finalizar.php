<?php session_start();
// CAMBIO EL ESTADO DEL DOCUMENTO A FINALIZADO
function finalizar($documento) {
	include "../conexion.php";
  $sql = "UPDATE documentos SET
          id_estado=(SELECT e.id
            				  FROM estados_documento AS e
            					WHERE e.nombre LIKE '%Finalizado%'),
          log_usuario=".$_SESSION['id_usuario'].",
          log_update='".date("Y-m-d H:i:s")."'
          WHERE id=".$documento;
  mysql_query($sql,$conex);
  mysql_close($conex);
}
// PERMISOS
if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {
  if (isset($_GET["ID"])) {
    finalizar($_GET["ID"]);
  }
} // FIN DE PERMISOS
header ("Location: ../../ingreso.php");
?>
