<?php session_start();

if($_SESSION['tipo'] == "administrador") {

   include "../conexion.php";

   $nombre = $_POST["NOM"];
   $desc = $_POST["DES"];
   $fecha = date("Y-m-d H:i:s");
   $id_usuario = $_SESSION['id_usuario'];

  	$sql  = "INSERT INTO estados_documento (nombre, descripcion, log_usuario, log_insert) VALUES ('".$nombre."','".$desc."', $id_usuario, '$fecha')";

  	$result = mysql_query($sql,$conex);

  	mysql_close($conex);
 }
 $referencia= $_SERVER['HTTP_REFERER'];
 header ("Location: ".$referencia);
 ?>
