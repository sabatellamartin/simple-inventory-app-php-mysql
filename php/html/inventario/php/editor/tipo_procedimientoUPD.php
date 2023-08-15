<?php session_start();

if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

	$id_tipo = $_POST["ID"];
	$descripcion = $_POST["DES"];
  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];

	$sql  = "UPDATE tipos_procedimiento SET
           descripcion='".$descripcion."',
           log_usuario='".$id_usuario."',
           log_update='".$fecha."'
           WHERE id='".$id_tipo."'";

	// EJECUTAR SENTENCIA SQL
	mysql_query($sql,$conex);

	mysql_close($conex);
}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: ".$referencia);
?>
