<?php session_start();

if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

	if (isset($_GET["DEL"])) {
		$id_rol = $_GET["DEL"];
	}

	$sql  = "DELETE FROM roles WHERE id='".$id_rol."'";

	// EJECUTAR SENTENCIA SQL
	mysql_query($sql,$conex);

	mysql_close($conex);
}
header ("Location: ../../editor.php?step=4");
?>
