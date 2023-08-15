<?php session_start();

if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

	if (isset($_GET["DEL"])) {
		$id_motivo = $_GET["DEL"];
	}

	$sql  = "DELETE FROM motivos_baja WHERE id='".$id_motivo."'";

	// EJECUTAR SENTENCIA SQL
	mysql_query($sql,$conex);

	mysql_close($conex);
}
header ("Location: ../../editor.php?step=7");
?>
