<?php session_start();

if($_SESSION['tipo'] == "administrador") {

  if (empty($_POST["PADRE"])) {
    header ("Location: ../../editor.php?step=1&ERR=Ingrese organismo padre");
  } else {

    $padre = $_POST["PADRE"];
    $nombre = $_POST["ORG"];
    $sigla = $_POST["SIG"];
    $fecha = date("Y-m-d H:i:s");
    $id_usuario = $_SESSION['id_usuario'];

    include "../conexion.php";
    $sql  = "INSERT INTO organismos (id_organismo, nombre, sigla, log_usuario, log_insert) VALUES ('".$padre."','".$nombre."','".$sigla."','".$id_usuario."','".$fecha."')";
    $result = mysql_query($sql,$conex);
  	mysql_close($conex);
    
    $referencia= $_SERVER['HTTP_REFERER'];
    header ("Location: ".$referencia);
  }
}
?>
