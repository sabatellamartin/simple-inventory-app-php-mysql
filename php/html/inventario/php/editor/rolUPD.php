<?php session_start();

if($_SESSION['tipo'] == "administrador") {

  include "../conexion.php";

	$id_rol = $_POST["ID"];
	$descripcion = $_POST["DES"];

	/*
	$sql = "SELECT descripcion FROM roles WHERE id='$id_rol'";
	$result = mysql_query($sql,$conex);
	while ($anterior = mysql_fetch_array($result)) {
		//$dato_anterior = "@CARGO:&".$anterior['cargo']."";
		$dato_anterior = htmlentities(addslashes("<table class='table table-bordered table-striped table-hover'>
										<tbody>
											<tr>
												<th>Columna</th>
												<th>Dato</th>
											</tr>
											<tr>
												<td><p class='text-info'>CARGO</p></td>
												<td>".$anterior['cargo']."</td>
											<tr>
										</tbody>
									</table>"));
	} // end while
	*/
  $fecha = date("Y-m-d H:i:s");
  $id_usuario = $_SESSION['id_usuario'];
	$sql  = "UPDATE roles SET
           descripcion='".$descripcion."',
           log_usuario='".$id_usuario."',
           log_update='".$fecha."'
           WHERE id='".$id_rol."'";

	// EJECUTAR SENTENCIA SQL
	mysql_query($sql,$conex);

	mysql_close($conex);
}
$referencia= $_SERVER['HTTP_REFERER'];
header ("Location: ".$referencia);
?>
