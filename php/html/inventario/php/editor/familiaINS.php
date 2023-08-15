<?php session_start();

if($_SESSION['tipo'] == "administrador") {

   include "../conexion.php";
   if (empty($_POST["FAM"])) {
     $referencia= $_SERVER['HTTP_REFERER'];
     header ("Location: ../../editor.php?step=2&ERR=Nombre");
   } else {
    	$nombre = $_POST["FAM"];
      $desc = $_POST["DES"];
      $padre = $_POST["PADRE"];
      $fecha = date("Y-m-d H:i:s");
      $id_usuario = $_SESSION['id_usuario'];

    if ($padre==""){
      $sql  = "INSERT INTO familias (id_familia, nombre, descripcion, log_usuario, log_insert) VALUES (NULL,'".$nombre."','".$desc."','".$id_usuario."','".$fecha."')";
    }else{
      $sql  = "INSERT INTO familias (id_familia, nombre, descripcion, log_usuario, log_insert) VALUES ('".$padre."','".$nombre."','".$desc."','".$id_usuario."','".$fecha."')";
    }

  	$result = mysql_query($sql,$conex);

  	mysql_close($conex);

  	$referencia= $_SERVER['HTTP_REFERER'];
  	header ("Location: ../../editor.php?step=2");
  }
}
?>
