<?php session_start();
//if($_SESSION['tipo'] == "administrador") {
  include "../conexion.php";
	$sql  = "UPDATE detalles_documento SET
           id_oficina='".$_POST['OFI']."',
           log_usuario='".$_SESSION['id_usuario']."',
           log_update='".date("Y-m-d H:i:s")."'
           WHERE id='".$_POST['ID']."'";

	mysql_query($sql,$conex);
	mysql_close($conex);
//}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: $referencia");
?>
