<?php session_start();
if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {
  include "../conexion.php";
  $sql  = "UPDATE documentos SET
           id_organismo_emisor='".$_POST["ORGE"]."',
					 observacion='".$_POST["OBS"]."',
           log_usuario='".$_SESSION['id_usuario']."',
           log_update='".date("Y-m-d H:i:s")."'
           WHERE id='".$_POST["ID"]."'";
  // EJECUTAR SENTENCIA SQL
  mysql_query($sql,$conex);
  mysql_close($conex);
}
header ("Location: ../../ingreso.php?step=2&ID=".$_POST["ID"]);
?>
