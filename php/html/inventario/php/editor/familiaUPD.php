<?php session_start();

if($_SESSION['tipo'] == "administrador") {

   include "../conexion.php";

	$id = $_POST["ID"];
	$nombre = $_POST["FAM"];
  $desc = $_POST["DES"];
  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];
  $sql  = "UPDATE familias SET
           nombre='".$nombre."',
           descripcion='".$desc."',
           log_usuario='".$id_usuario."',
           log_update='".$fecha."'
           WHERE id='".$id."'";

  // EJECUTAR SENTENCIA SQL
  mysql_query($sql,$conex);

  mysql_close($conex);
  }
  $referencia= $_SERVER['HTTP_REFERER'];
  header ("Location: ../../editor.php?step=2");
  ?>
