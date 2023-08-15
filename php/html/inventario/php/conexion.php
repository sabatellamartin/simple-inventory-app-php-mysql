<?php
  include "db_access.php";
	// CONECTAR AL SERVIDOR DE BASE DE DATOS
	$conex = mysql_connect($host,$user,$pass);

	// CONTROLAR CONEXION
	if (!$conex) { ?>
		<script>alert('ATENCION!!!.. Conexión al SERVIDOR fallida');</script>
		<?php
	} // endif
	// SELECCIONAR BASE DE DATOS
	$selDB = mysql_select_db($database,$conex);

	// CONTROLAR SELECCION DE BASE DE DATOS
	if (!$selDB) { ?>
		<script>alert('ATENCION!!!.. Selección de Base de Datos fallida');</script>
		<?php
	} // endif
?>
