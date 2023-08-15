<?php session_start();


if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {


  if (empty($_POST["ART"])) {
   $referencia= $_SERVER['HTTP_REFERER'];
   header ("Location: ../../articulo.php?step=1&ERR=El nombre del artículo no puede ser vacío.");
  } else if (empty($_POST["FAM"])) {
   $referencia= $_SERVER['HTTP_REFERER'];
   header ("Location: ../../articulo.php?step=1&ERR=No ha seleccionado una familia.");
  } else if (empty($_POST["SUB"])) {
   $referencia= $_SERVER['HTTP_REFERER'];
   header ("Location: ../../articulo.php?step=1&ERR=No ha seleccionado una subfamilia.");
  } else {

    include "../conexion.php";

  	$id = $_POST["ID"];
  	$nombre = $_POST["ART"];
    $desc = $_POST["DES"];
  	$familia = $_POST["SUB"];
    $fecha = date("Y-m-d H:i:s");
    $id_usuario = $_SESSION['id_usuario'];
    $sql  = "UPDATE articulos SET
             id_familia='".$familia."',
             nombre='".$nombre."',
  					 descripcion='".$desc."',
             log_usuario='".$id_usuario."',
             log_update='".$fecha."'
             WHERE id='".$id."'";
             
    // EJECUTAR SENTENCIA SQL
    mysql_query($sql,$conex);

    mysql_close($conex);
  }
}
header ("Location: ../../articulo.php");
?>
