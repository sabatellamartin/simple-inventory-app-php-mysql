<?php session_start();

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {
  if (empty($_POST["NOM"])) {
    header ("Location: ../../familia.php?step=2&ERR=El nombre de la familia no puede ser vacío.");
  } else {
    include "../conexion.php";
  	$id = $_POST["ID"];
  	$nombre = $_POST["NOM"];
    $desc = $_POST["DES"];
  	$familia = $_POST["FAM"];
    $codigo = $_POST["COD"];
    $fecha = date("Y-m-d H:i:s");
    $id_usuario = $_SESSION['id_usuario'];
    if (empty($_POST["FAM"])) {
      $sql  = "UPDATE familias SET
               codigo='".$codigo."',
               nombre='".$nombre."',
    					 descripcion='".$desc."',
               log_usuario='".$id_usuario."',
               log_update='".$fecha."'
               WHERE id='".$id."'";
    } else {
      $sql  = "UPDATE familias SET
               id_familia='".$familia."',
               codigo='".$codigo."',
               nombre='".$nombre."',
    					 descripcion='".$desc."',
               log_usuario='".$id_usuario."',
               log_update='".$fecha."'
               WHERE id='".$id."'";
    }
    // EJECUTAR SENTENCIA SQL
    mysql_query($sql,$conex);
    mysql_close($conex);
    $referencia= $_SERVER['HTTP_REFERER'];
    header ("Location: ../../familia.php");
  }
}
?>
