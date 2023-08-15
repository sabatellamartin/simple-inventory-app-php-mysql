<?php session_start();?>
<!DOCTYPE HTML>
<html>
<?php include ('layout/header.php'); ?>
<body>
  <div class="container">
    <?php include ('layout/navbar.php'); ?>

	   <?php
     if ($login==true && ($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario')) {

   		$step = "";

   		if (isset($_GET["step"])) {

	   		$paso = $_GET["step"];

	   		if($paso=='1') {
	   			include ('php/adjudicatario/adjudicatarioINS.php');
	   		} elseif($paso=='2') {
					include ('php/adjudicatario/adjudicatarioUPD.php');
	   		}

	   } else {
			include ('php/adjudicatario/adjudicatario.php');
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
