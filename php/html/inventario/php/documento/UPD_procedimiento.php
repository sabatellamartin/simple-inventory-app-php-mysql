<?php session_start();

if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {

  include "../conexion.php";

  $sql  = "UPDATE detalles_documento SET
           id_tipo_procedimiento='".$_POST["PRO"]."',
					 numero_procedimiento='".$_POST["NRO"]."',
           id_adjudicatario='".$_POST["ADJ"]."',
					 numero_factura='".$_POST["FAC"]."',
					 plazo_garantia='".$_POST["PLA"]."',
					 fecha_factura='".$_POST["FEC"]."',
           log_usuario='".$_SESSION['id_usuario']."',
           log_update='".date("Y-m-d H:i:s")."'
           WHERE id='".$_POST["ID"]."'";

  // EJECUTAR SENTENCIA SQL
  mysql_query($sql,$conex);
  mysql_close($conex);
}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: $referencia");
?>
