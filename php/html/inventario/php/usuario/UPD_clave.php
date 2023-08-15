<?php session_start();
if (isset($_SESSION['id_usuario'])) {
  include "../conexion.php";
  // SI EL NUEVO Y RE PASS SON IGUALES
  if ($_POST["NEW"]==$_POST["REP"]) {
  	$sql = "SELECT id, usuario, clave FROM usuarios WHERE id=".$_SESSION['id_usuario']."";
  	$result = mysql_query($sql,$conex);
    $u = mysql_fetch_array($result);
    // SI LA PASS VIEJA ES VALIDA
    if (md5($_POST["OLD"])==$u[2]) {
      $sql  = "UPDATE usuarios SET
            clave='".md5($_POST["NEW"])."',
            log_update='".date("Y-m-d H:i:s")."',
            log_usuario=".$_SESSION['id_usuario']."
            WHERE id=".$_SESSION['id_usuario']."";
      echo $sql;
      // EJECUTAR SENTENCIA SQL
      mysql_query($sql,$conex);
      mysql_close($conex);
    } else {
      echo "ENTRA1";
      header ("Location: ../../usuario.php?ERR=Contraseña actual no coincide.");
    }
  } else {
    echo "ENTRA1";
    header ("Location: ../../usuario.php?ERR=La repetición, no coincide con su constraseña nueva.");
  }
}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: $referencia");
?>
