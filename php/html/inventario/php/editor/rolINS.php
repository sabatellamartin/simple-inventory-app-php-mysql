<?php session_start();

if($_SESSION['tipo'] == "administrador") {

   include "../conexion.php";

	$descripcion = $_POST["DES"];
  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];

	$sql  = "INSERT INTO roles (descripcion, log_usuario, log_insert) VALUES ('".$descripcion."', $id_usuario, '$fecha')";

	$result = mysql_query($sql,$conex);
  /*
	$sql = "SELECT MAX(id) FROM roles";
	$result = mysql_query($sql,$conex);
	while ($rol = mysql_fetch_array($result)) {
		$id=$rol['MAX(id)'];
	} // end while
  */
	mysql_close($conex);

}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: ".$referencia);
?>
