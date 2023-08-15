<?php session_start();

if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

	$rol_id = $_POST["ROL"];
	$usuario = $_POST["USU"];
	$nombre = $_POST["NOM"];
	$apellido = $_POST["APE"];
	$email = $_POST["EMA"];
  // Cambiar a SHA1
	$clave = md5($_POST["PAS"]);

  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];

  /*
  Falta armar algun mecanismo de cambio de contraseña a traves de mail.
  */

  $sql = "SELECT email, usuario
          FROM usuarios
          WHERE usuario='".$usuario."'
          OR email='".$email."'";
  $result = mysql_query($sql,$conex);
  if(mysql_num_rows($res) != 0) {
    ?> <script>alert('El nombre de usuario o email ya existen en el sistema.');</script> <?php
  } else {
  	$sql  = "INSERT INTO usuarios (id_rol,usuario,nombre, apellido,email,clave,log_usuario,log_insert)
             VALUES ($rol_id, '".$usuario."','".$nombre."','".$apellido."','".$email."','".$clave."', $id_usuario, '$fecha')";
   	// EJECUTAR SENTENCIA SQL
   	mysql_query($sql,$conex);
  }
  mysql_close($conex);
}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: ".$referencia);
?>
