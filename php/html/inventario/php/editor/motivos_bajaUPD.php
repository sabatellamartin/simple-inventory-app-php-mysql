<?php session_start();

if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

	$id_motivo = $_POST["ID"];
	$motivo = $_POST["MOT"];
  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];

	$sql  = "UPDATE motivos_baja SET
           motivo='".$motivo."',
           log_usuario='".$id_usuario."',
           log_update='".$fecha."'
           WHERE id='".$id_motivo."'";

	// EJECUTAR SENTENCIA SQL
	mysql_query($sql,$conex);

	mysql_close($conex);
}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: ".$referencia);
?>
