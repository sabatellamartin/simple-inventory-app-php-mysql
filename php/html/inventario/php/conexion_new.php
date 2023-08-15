<?php
  include "db_access.php";
  // NUEVA CONEXION (Para versiones de PHP mayores a 5.6)
  $connection = mysqli_connect($host,$user,$pass,$database) or die (mysqli_connect_error().": ".mysqli_connect_error());
?>
