<?php session_start();


// CARGO OPCIONES ESTADO
function existeArticulo($nombre) {
  $ret = false;
	include "../conexion.php";
	$sql = "SELECT a.id,
								 a.nombre
				  FROM articulos AS a
					WHERE a.nombre = '$nombre'";
	$result = mysql_query($sql,$conex);
  if(mysql_num_rows($result) != 0) {
     $ret = true;
	}
	mysql_close($conex);
  return $ret;
}

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {
echo existeArticulo($_POST["ART"]);

   if (empty($_POST["ART"])) {
     $referencia= $_SERVER['HTTP_REFERER'];
     header ("Location: ../../articulo.php?step=1&ERR=El nombre del artículo no puede ser vacío.");
   } else if (empty($_POST["FAM"])) {
     $referencia= $_SERVER['HTTP_REFERER'];
     header ("Location: ../../articulo.php?step=1&ERR=No ha seleccionado una familia.");
   } else if (empty($_POST["SUB"])) {
     $referencia= $_SERVER['HTTP_REFERER'];
     header ("Location: ../../articulo.php?step=1&ERR=No ha seleccionado una subfamilia.");
   } else if (existeArticulo($_POST["ART"])) {
     header ("Location: ../../articulo.php?step=1&ERR=Nombre de artículo existente.");
   } else {

  	$nombre = $_POST["ART"];
    $desc = $_POST["DES"];
  	$familia = $_POST["SUB"];
    $fecha = date("Y-m-d H:i:s");
    $id_usuario = $_SESSION['id_usuario'];

    include "../conexion.php";
  	$sql  = "INSERT INTO articulos (id_familia, nombre, descripcion, log_usuario, log_insert) VALUES ('".$familia."','".$nombre."','".$desc."','".$id_usuario."','".$fecha."')";
  	$result = mysql_query($sql,$conex);
  	mysql_close($conex);
    $referencia= $_SERVER['HTTP_REFERER'];
    header ("Location: ../../articulo.php");

  }
}
?>
