<?php session_start();

function existeFamiliaNombre($nombre) {
  $ret = false;
	include "../conexion.php";
	$sql = "SELECT f.id,
								 f.nombre
				  FROM familias AS f
					WHERE f.nombre = '$nombre'";
	$result = mysql_query($sql,$conex);
  if(mysql_num_rows($result) != 0) {
     $ret = true;
	}
	mysql_close($conex);
  return $ret;
}

function existeFamiliaCodigo($codigo) {
  $ret = false;
	include "../conexion.php";
	$sql = "SELECT f.id,
								 f.codigo
				  FROM familias AS f
					WHERE f.codigo = '$codigo'";
	$result = mysql_query($sql,$conex);
  if(mysql_num_rows($result) != 0) {
     $ret = true;
	}
	mysql_close($conex);
  return $ret;
}

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {

   if (empty($_POST["NOM"])) {
     $referencia= $_SERVER['HTTP_REFERER'];
     header ("Location: ../../familia.php?step=1&ERR=El nombre de la familia no puede ser vacío.");
   //} else if (empty($_POST["FAM"])) {
     //$referencia= $_SERVER['HTTP_REFERER'];
     //header ("Location: ../../familia.php?step=1&ERR=No ha seleccionado una familia.");
   } else if (existeFamiliaNombre($_POST["NOM"])) {
     header ("Location: ../../familia.php?step=1&ERR=Nombre de familia existente.");
   } else if (existeFamiliaCodigo($_POST["COD"])) {
     header ("Location: ../../familia.php?step=1&ERR=Código de familia existente.");
   } else {

    include "../conexion.php";
    if (empty($_POST["FAM"])) {
  	$sql  = "INSERT INTO familias (codigo, nombre, descripcion, log_usuario, log_insert)
    VALUES ('".$_POST["COD"]."','".$_POST["NOM"]."','".$_POST["DES"]."','".$_SESSION['id_usuario']."','".date("Y-m-d H:i:s")."')";
    } else {
      $sql  = "INSERT INTO familias (id_familia, codigo, nombre, descripcion, log_usuario, log_insert)
      VALUES (".$_POST["FAM"].",'".$_POST["COD"]."','".$_POST["NOM"]."','".$_POST["DES"]."','".$_SESSION['id_usuario']."','".date("Y-m-d H:i:s")."')";
    }
    echo $sql;
  	$result = mysql_query($sql,$conex);
  	mysql_close($conex);

    $referencia= $_SERVER['HTTP_REFERER'];
    header ("Location: ../../familia.php");

  }
}
?>
