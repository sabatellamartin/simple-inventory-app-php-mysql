<?php session_start();

if($_SESSION['tipo'] == "administrador") {
  include "../conexion.php";
	if (isset($_GET["DEL"])) {
		$id = $_GET["DEL"];
	}
  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];

	$sql  = "UPDATE usuarios SET
        log_usuario=".$id_usuario.",
        fecha_baja='".$fecha."'
				WHERE id=".$id."";

 	mysql_query($sql,$conex);
  mysql_close($conex);
}
header ("Location: ../../editor.php?step=5");
?>
