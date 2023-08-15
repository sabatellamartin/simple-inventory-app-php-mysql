<?php session_start();

if($_SESSION['tipo'] == "administrador") {

   include "../conexion.php";

   $id = $_POST["ID"];
   $nombre = $_POST["NOM"];
 	 $descripcion = $_POST["DES"];
   $fecha = date("Y-m-d H:i:s");
   $id_usuario = $_SESSION['id_usuario'];

 	$sql  = "UPDATE estados_documento SET
            nombre='".$nombre."',
            descripcion='".$descripcion."',
            log_usuario='".$id_usuario."',
            log_update='".$fecha."'
            WHERE id='".$id."'";

 	// EJECUTAR SENTENCIA SQL
 	mysql_query($sql,$conex);

 	mysql_close($conex);
 }
 $referencia= $_SERVER['HTTP_REFERER'];
 header ("Location: ../../editor.php");
 ?>
