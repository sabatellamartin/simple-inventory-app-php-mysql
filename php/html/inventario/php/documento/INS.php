<?php session_start();

//if($_SESSION['tipo'] == "administrador") {

   include "../conexion.php";

   $rol = $_SESSION['tipo'];

   if ($rol=='administrador' || $rol=='operador inventario' || $rol=='operador'){
     if (empty($_POST["ORG"])) {
       $referencia= $_SERVER['HTTP_REFERER'];
     	header ("Location: ../../documento.php?step=1&ERR=Receptor");
    } else{
      $tipo = 6;
      $orgR = $_POST["ORG"];
    }
  } else {
    $tipo = 7;
    $orgR = 7;
  }
	/*if (empty($_POST["TIPO"])) {
    $referencia= $_SERVER['HTTP_REFERER'];
  	header ("Location: ../../documento.php?step=1&ERR=Tipo");
  } else */
  if (empty($_POST["ORGE"])) {
    $referencia= $_SERVER['HTTP_REFERER'];
  	header ("Location: ../../documento.php?step=1&ERR=Emisor");
  } else {
    //$tipo = $_POST["TIPO"];
  	$orgE = $_POST["ORGE"];
  	$obs = $_POST["OBS"];
  	$fecha = date("Y-m-d H:i:s");
    $id_usuario = $_SESSION['id_usuario'];

  	$sql  = "INSERT INTO documentos (id_tipo_documento, id_estado, id_organismo_emisor, id_usuario_emisor, id_organismo_receptor, id_usuario_receptor, observacion, log_usuario, log_insert,emision) VALUES ('".$tipo."',4,'".$orgE."','".$id_usuario."','".$orgR."',NULL,'".$obs."','".$id_usuario."','".$fecha."','".$fecha."')";

  	$result = mysql_query($sql,$conex);
    $doc = mysql_insert_id($conex);
  	mysql_close($conex);


	$referencia= $_SERVER['HTTP_REFERER'];
  if ($rol=='operador inventario') {
    header ("Location: ../../documento.php?step=2&ID=".$doc."");
  } else{
    header ("Location: ../../documento.php?step=8&ID=".$doc."");
  }

  }
//}
?>
