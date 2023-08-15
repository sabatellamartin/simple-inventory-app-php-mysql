<?php session_start();

if($_SESSION['tipo'] == "administrador") {

   include "../conexion.php";

	$id = $_POST["ID"];
	$tipo = $_POST["TIPO"];
  $estado = $_POST["EST"];
	$orgE = $_POST["ORGE"];
	$usuE = $_POST["USUE"];
	$orgR = $_POST["ORG"];
	$obs = $_POST["OBS"];
  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];
  $sql  = "UPDATE documentos SET
           id_tipo_documento='".$tipo."',
					 id_estado='".$estado."',
           id_organismo_emisor='".$orgE."',
					 id_usuario_emisor='".$id_usuario."',
					 id_organismo_receptor='".$orgR."',
					 observacion='".$obs."',
           log_usuario='".$id_usuario."',
           log_update='".$fecha."'
           WHERE id='".$id."'";

  // EJECUTAR SENTENCIA SQL
  mysql_query($sql,$conex);

  mysql_close($conex);
  }
  $referencia= $_SERVER['HTTP_REFERER'];
  header ("Location: ../../documento.php");
  ?>
