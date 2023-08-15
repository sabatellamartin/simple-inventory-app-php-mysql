<?php session_start();

if($_SESSION['tipo'] == "administrador" || $_SESSION['tipo'] == "editor") {
		// CONECAR AL SERVIDOR
		include "../conexion.php";

		// CAPTURAR DATOS DEL FORMULARIO
		$id_usr = $_POST["IDUSR"];
		$id_org = $_POST["IDORG"];

		$fecha = date("Y-m-d H:i:s");
	  $id_usuario = $_SESSION['id_usuario'];

		// ARMAR SENTENCIA
		$sql = "INSERT INTO usuarios_organismos (id_usuario, id_organismo,log_usuario,log_insert) VALUES ('".$id_usr."','".$id_org."', $id_usuario, '$fecha')";

		// EJECUTAR SENTENCIA SQL
		mysql_query($sql,$conex);

		// CERRAR CONEXION
		mysql_close($conex);
}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: ".$referencia."");
?>
