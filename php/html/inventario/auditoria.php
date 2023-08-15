<?php session_start();?>
<!DOCTYPE HTML>
<html>
<?php include ('layout/header.php'); ?>
<body>
  <div class="container">
    <?php include ('layout/navbar.php'); ?>

    <?php if ($login==true && $_SESSION['tipo']=='administrador') {
    	  		include ('php/auditoria/auditoria.php');
    	    } else { ?>
    				<script type="text/javascript">setTimeout(function(){window.location="index.php"},0);</script>
    <?php	} ?>

  <?php include ('layout/footer.php'); ?>
  </div> <!-- /container -->
</body>
</html>
