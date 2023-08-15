<?php session_start();

if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

	if (isset($_GET["DEL"])) {
		$id_fam = $_GET["DEL"];
	}

	$sql  = "DELETE FROM familias WHERE id='".$id_fam."'";

	// EJECUTAR SENTENCIA SQL
	mysql_query($sql,$conex);

	mysql_close($conex);
}
header ("Location: ../../editor.php?step=2");
?>
