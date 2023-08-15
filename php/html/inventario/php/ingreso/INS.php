<?php session_start();
if ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') {
  if (empty($_POST["ORGE"])) {
    header ("Location: ../../ingreso.php?step=1&ERR=Ingrese unidad de emisión del documento");
  } elseif (empty($_POST["ORGR"])) {
    header ("Location: ../../ingreso.php?step=1&ERR=Ingrese unidad de destino del documento");
  } else {
    include "../conexion.php";
    // 6 Documento de Entrada, 8 Estado en Proceso
    $sql  = "INSERT INTO documentos (
                id_tipo_documento,
                id_estado,
                id_organismo_emisor,
                id_usuario_emisor,
                id_organismo_receptor,
                id_usuario_receptor,
                observacion,
                log_usuario,
                log_insert,
                emision)
             VALUES (
                6,
                8,
                '".$_POST["ORGE"]."',
                '".$_SESSION['id_usuario']."',
                '".$_POST["ORGR"]."',
                NULL,
                '".$_POST["OBS"]."',
                '".$_SESSION['id_usuario']."',
                '".date("Y-m-d H:i:s")."',
                '".date("Y-m-d H:i:s")."')";
    $result = mysql_query($sql,$conex);
    $id = mysql_insert_id($conex);
    mysql_close($conex);
    header ("Location: ../../ingreso.php?step=2&ID=".$id."");
  }
}
?>
