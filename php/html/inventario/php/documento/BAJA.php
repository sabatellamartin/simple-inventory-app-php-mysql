<?php session_start();

if($_SESSION['tipo'] == "administrador" || $_SESSION['tipo'] == "operador") {

   include "../conexion.php";

	$id = $_POST["ID"];
	$motivo = $_POST["MOT"];
	$obs = $_POST["OBS"];
  if (isset($_POST["CANT"])) {
  	$cant = $_POST["CANT"];
  } else {
    $cant = '1';
  }

  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];

  $sql2 = "SELECT id_articulo, id_oficina, cantidad, observacion, id_tipo_procedimiento, numero_procedimiento, id_adjudicatario, numero_factura, fecha_factura, plazo_garantia FROM detalles_documento WHERE id='".$id."'";
  $result = mysql_query($sql2, $conex);
  $dir=mysql_fetch_array($result);
  $cant_detalle=$dir['cantidad'];
  $cant_tot = $cant_detalle - $cant;
  if ($cant_tot > 0){
    $sql  = "UPDATE detalles_documento SET
  					 cantidad='".$cant_tot."',
             log_usuario='".$id_usuario."',
             log_update='".$fecha."'
             WHERE id='".$id."'";
    $sql3  = "INSERT INTO detalles_documento (id_articulo, cantidad, observacion, id_oficina, fecha_baja, observacion_baja, log_usuario, log_insert, log_update) VALUES ('".$dir['id_articulo']."','".$cant."','".$dir['observacion']."','".$motivo."','".$fecha."','".$obs."','".$id_usuario."','".$fecha."','".$fecha."')";
    $result2 = mysql_query($sql3,$conex);
    $doc = mysql_insert_id($conex);
  } else {
    $sql  = "UPDATE detalles_documento SET
  					 id_oficina='".$motivo."',
  					 observacion_baja='".$obs."',
             fecha_baja='".$fecha."',
             log_usuario='".$id_usuario."',
             log_update='".$fecha."'
             WHERE id='".$id."'";
  }

  // EJECUTAR SENTENCIA SQL
  mysql_query($sql,$conex);

  mysql_close($conex);
  }
  $referencia= $_SERVER['HTTP_REFERER'];
  header ("Location: ../../baja.php");
  ?>
