<?php session_start();?>
<!DOCTYPE HTML>
<html>
<?php include ('layout/header.php'); ?>
<body>
  <div class="container">
    <?php include ('layout/navbar.php'); ?>

	<?php
	if ($login==true && ($_SESSION['tipo']=='administrador')) {

		$step = "";

		if (isset($_GET["step"])) {

	   	$step = $_GET["step"];

	   	if($step=='1') {
	   		include ('php/editor/menu.php');
	   		include ('php/editor/organismo.php');
	   	} elseif($step=='2') {
	   		include ('php/editor/menu.php');
				include ('php/editor/familia.php');
	   	} elseif($step=='3') {
	   		include ('php/editor/menu.php');
				include ('php/editor/tipo_documento.php');
	   	} elseif($step=='4') {
	   		include ('php/editor/menu.php');
				include ('php/editor/rol.php');
	   	} elseif($step=='5') {
	   		include ('php/editor/menu.php');
				include ('php/editor/usuario.php');
	   	} elseif($step=='6') {
	   		include ('php/editor/menu.php');
				include ('php/editor/tipo_procedimiento.php');
	   	} elseif($step=='7') {
        include ('php/editor/menu.php');
        include ('php/editor/motivos_baja.php');
      }
	   } else {
			include ('php/editor/menu.php');
			include ('php/editor/estados_documento.php');
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
  </div> <!-- /container -->
</body>
</html>
