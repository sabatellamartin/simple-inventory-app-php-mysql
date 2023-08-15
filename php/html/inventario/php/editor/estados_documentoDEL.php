<?php session_start();

if($_SESSION['tipo'] == "administrador") {

    include "../conexion.php";

  	if (isset($_GET["DEL"])) {
  		$id = $_GET["DEL"];
  	}

  	$sql  = "DELETE FROM estados_documento WHERE id='".$id."'";

  	// EJECUTAR SENTENCIA SQL
  	mysql_query($sql,$conex);

  	mysql_close($conex);
  }
  header ("Location: ../../editor.php");
  ?>
