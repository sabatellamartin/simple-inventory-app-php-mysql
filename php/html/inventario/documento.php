<?php session_start();?>
<!DOCTYPE HTML>
<html>
<?php include ('layout/header.php'); ?>
<body>
  <div class="container">
    <?php include ('layout/navbar.php'); ?>

	   <?php
     if ($login==true && ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario' || $_SESSION['tipo']=='operador oficina' || $_SESSION['tipo']=='autorizador') {

   		$step = "";

   		if (isset($_GET["step"])) {

	   		$paso = $_GET["step"];

	   		if($paso=='1') {
	   			include ('php/documento/documentoINS.php');
	   		} elseif($paso=='2') {
					include ('php/documento/documentoUPD.php');
	   		} elseif($paso=='3') {
					include ('php/documento/ver.php');
	   		} elseif($paso=='4') {
					include ('php/documento/cambia_estado.php');
	   		} elseif($paso=='5') {
					include ('php/documento/detalles_documento.php');
	   		} elseif($paso=='6') {
					include ('php/documento/ver_item.php');
	   		} elseif($paso=='7') {
					include ('php/documento/reasignar_item.php');
	   		} elseif($paso=='8') {
					include ('php/documento/movimiento.php');
	   		} elseif($paso=='9') {
					include ('php/documento/modificar_procedimiento.php');
	   		}
      } else{
        include ('php/documento/documento.php');
     	} // fin del if step

  } else{
	?>
			<script type="text/javascript">setTimeout(function(){window.location="index.php"},0);</script>
	<?php
	}
	?>

		<hr>


    </div> <!-- /container -->

    <?php include ('layout/footer.php'); ?>

  </body>
</html>
