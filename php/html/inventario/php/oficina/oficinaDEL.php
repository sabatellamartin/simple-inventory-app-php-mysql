<?php session_start();

//if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

	if (isset($_GET["DEL"])) {
		$id = $_GET["DEL"];
	}

	$sql="DELETE FROM oficinas WHERE id='".$id."'";

	// EJECUTAR SENTENCIA SQL
	mysql_query($sql,$conex);

	mysql_close($conex);
//}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: ../../oficina.php?step=2");
?>
