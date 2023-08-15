<?php session_start();

// CARGO OPCIONES ESTADO
function existeRut($rut) {
  $ret = false;
	include "../conexion.php";
	$sql = "SELECT a.id,
								 a.nombre,
                 a.rut
				  FROM adjudicatarios AS a
					WHERE a.rut = '$rut'";
	$result = mysql_query($sql,$conex);
  if(mysql_num_rows($result) != 0) {
     $ret = true;
	}
	mysql_close($conex);
  return $ret;
}


if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {

  include "../conexion.php";

  if (empty($_POST["NOM"])) {
   $referencia= $_SERVER['HTTP_REFERER'];
   header ("Location: ../../adjudicatario.php?step=2&ERR=Nombre no puede ser vac&iacute;o.");
  } else if (empty($_POST["RUT"])) {
   $referencia= $_SERVER['HTTP_REFERER'];
   header ("Location: ../../adjudicatario.php?step=2&ERR=RUT no puede ser vac&iacute;o.");
  } else if (existeRut($_POST["RUT"])) {
   header ("Location: ../../adjudicatario.php?step=2&ERR=RUT de adjudicatario existente.");
  } else {
  	$id = $_POST["ID"];
  	$nombre = $_POST["NOM"];
    $descripcion = $_POST["DES"];
  	$rut = $_POST["RUT"];
  	$contacto = $_POST["CON"];
  	$telefono = $_POST["TEL"];
  	$email = $_POST["EMA"];
    $fecha = date("Y-m-d H:i:s");
    $id_usuario = $_SESSION['id_usuario'];
    $sql  = "UPDATE adjudicatarios SET
             rut='".$rut."',
             nombre='".$nombre."',
             descripcion='".$descripcion."',
             contacto='".$contacto."',
             telefono='".$telefono."',
  					 email='".$email."',
             log_usuario='".$id_usuario."',
             log_update='".$fecha."'
             WHERE id='".$id."'";
    // EJECUTAR SENTENCIA SQL
    mysql_query($sql,$conex);
    mysql_close($conex);
  }
}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: ../../adjudicatario.php");
?>
