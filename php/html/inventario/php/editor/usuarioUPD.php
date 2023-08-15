<?php session_start();

if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

  $id = $_POST["ID"];
  $rol_id = $_POST["ROL"];
  $usuario = $_POST["USU"];
  $nombre = $_POST["NOM"];
  $apellido = $_POST["APE"];
  $email = $_POST["EMA"];
  // Cambiar a SHA1
  $clave = md5($_POST["PAS"]);

  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];

	$sql  = "UPDATE usuarios SET
				id_rol=".$rol_id.",
				usuario='".$usuario."',
        nombre='".$nombre."',
        apellido='".$apellido."',
        email='".$email."',
			 	clave='".$clave."',
        log_update='".$fecha."',
        log_usuario=".$id_usuario."
				WHERE id=".$id."";

 	mysql_query($sql,$conex);

  mysql_close($conex);
}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: ".$referencia);
?>
