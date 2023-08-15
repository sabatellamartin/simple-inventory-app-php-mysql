<?php session_start();

if($_SESSION['tipo'] == "administrador") {

   include "../conexion.php";

	$id = $_POST["ID"];
	$nombre = $_POST["ORG"];
  $sigla = $_POST["SIG"];
  $padre = $_POST["PADRE"];
  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];
  if ($padre!="") {
  $sql  = "UPDATE organismos SET
           id_organismo='".$padre."',
           nombre='".$nombre."',
           sigla='".$sigla."',
           log_usuario='".$id_usuario."',
           log_update='".$fecha."'
           WHERE id='".$id."'";
  } else {
    $sql  = "UPDATE organismos SET
             nombre='".$nombre."',
             sigla='".$sigla."',
             log_usuario='".$id_usuario."',
             log_update='".$fecha."'
             WHERE id='".$id."'";
  }
  // EJECUTAR SENTENCIA SQL
  mysql_query($sql,$conex);

  mysql_close($conex);
  }
  $referencia= $_SERVER['HTTP_REFERER'];
  header ("Location: ../../editor.php?step=1");
  ?>
