<?php session_start();

if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

  if ($_POST["MOT"]!="") {
    $motivo = $_POST["MOT"];
    $fecha = date("Y-m-d H:i:s");
    $id_usuario = $_SESSION['id_usuario'];
   	$sql  = "INSERT INTO motivos_baja (motivo, log_usuario, log_insert) VALUES ('".$motivo."', $id_usuario, '$fecha')";
   	$result = mysql_query($sql,$conex);
 	  mysql_close($conex);
  }
}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: ".$referencia);
?>
