<?php session_start();

//if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

	$sql  = "UPDATE oficinas SET
           id_organismo='".$_POST['ORG']."',
           nombre='".$_POST['NOM']."',
           log_usuario='".$_SESSION['id_usuario']."',
           log_update='".date("Y-m-d H:i:s")."'
           WHERE id='".$_POST['ID']."'";

	// EJECUTAR SENTENCIA SQL
	mysql_query($sql,$conex);

	mysql_close($conex);
//}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: $referencia");
?>
