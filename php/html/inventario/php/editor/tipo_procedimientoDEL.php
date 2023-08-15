<?php session_start();

if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

	if (isset($_GET["DEL"])) {
		$id_tipo = $_GET["DEL"];
	}

	$sql  = "DELETE FROM tipos_procedimiento WHERE id='".$id_tipo."'";

	// EJECUTAR SENTENCIA SQL
	mysql_query($sql,$conex);

	mysql_close($conex);
}
header ("Location: ../../editor.php?step=6");
?>
