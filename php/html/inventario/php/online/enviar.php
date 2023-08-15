<?php
session_start();
header ('refresh:2; url=contacto.php');

$cuenta       = 'user@domain.com';
$nombre       = $_POST['nombre'];
$mail         = $_POST['mail'];
$telefono     = $_POST['telefono'];
$asunto       = $_POST['asunto'];
$consulta     = $_POST['consulta'];


if ($nombre == "" || $mail == "" || $telefono == "" || $consulta == "") {
	header ("Location: ../../index.php?step=5");
} else{
	$header   = 'From: ' . $mail . " \r\n";
	$header  .= "X-Mailer: PHP/" . phpversion() . " \r\n";
	$header  .= "Mime-Version: 1.0 \r\n";
	$header  .= "Content-Type: text/plain";
	$mensaje  = "Este mensaje fue enviado por: " . $nombre . " \r\n";
	$mensaje .= "Su e-mail es: " . $mail . " \r\n";
	$mensaje .= "Su telefono es: " . $telefono . " \r\n";
	$mensaje .= "Mensaje: " . $consulta . " \r\n";
	$mensaje .= "Enviado el " . date('d/m/Y', time()). " \r\n";

	$asunto   = 'Consulta desde WEB';
	ini_set(sendmail_from,$cuenta);

	mail($cuenta, $asunto, utf8_decode($mensaje), $header);
	header ("Location: ../../index.php?step=4");
}
?>
