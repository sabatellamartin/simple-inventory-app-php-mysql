<?php session_start();

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {
  include "../conexion.php";
	if (isset($_GET["DEL"])) {
		$id = $_GET["DEL"];
	}
  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];

	$sql="DELETE FROM familias WHERE id=$id";

 	mysql_query($sql,$conex);
  mysql_close($conex);
}
header ("Location: ../../familia.php");
?>
