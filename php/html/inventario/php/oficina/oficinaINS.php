<?php session_start();

//if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

  $fecha = date("Y-m-d H:i:s");
 	$sql  = "INSERT INTO oficinas (id_organismo, nombre, log_usuario, log_insert) VALUES ('".$_POST['ORG']."','".$_POST['NOM']."', ".$_SESSION['id_usuario'].", '$fecha')";
 	$result = mysql_query($sql,$conex);
	mysql_close($conex);

//}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: $referencia");
?>
