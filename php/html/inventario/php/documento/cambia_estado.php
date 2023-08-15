<?php session_start();

include "php/conexion.php";

$id_documento = $_GET["ID"];
$tipo = $_GET["TIPO"];

$fecha = date("Y-m-d H:i:s");
$id_usuario = $_SESSION['id_usuario'];
$sql="SELECT id_estado FROM documentos WHERE id = '$id_documento'";
$res = mysql_query($sql,$conex);
$doc = mysql_fetch_row($res);
$estado = $doc[0];

if ($tipo == 1){
  if ($estado == 4){
    $nuevo_estado = 8;
  } elseif ($estado == 8) {
    $nuevo_estado = 5;
  } elseif ($estado == 5) {
    $nuevo_estado = 6;
  } else {
    $nuevo_estado = 7;
  }
} else {
  if  ($tipo == 2) {
    if ($estado == 5 || $estado == 6 || $estado == 8) {
    $nuevo_estado = 4;
    }
  }
}

$sql  = "UPDATE documentos SET
         id_estado='".$nuevo_estado."',
         log_usuario='".$id_usuario."',
         log_update='".$fecha."'
         WHERE id='".$id_documento."'";
// EJECUTAR SENTENCIA SQL
mysql_query($sql,$conex);
mysql_close($conex);

$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: ../../documento.php");
?>
