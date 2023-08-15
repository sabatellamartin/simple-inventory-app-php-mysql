<?php session_start(); ?>
<!DOCTYPE HTML>
<html>

<?php include ('layout/header.php'); ?>

<body>


    <div class="container">

      <div class="row" style="margin-top:50px;margin-bottom:200px;">
        <div class="span3"></div>
        <div class="span6">
          <div align="center">
            <img src="assets/imagen/logo/logo.png" alt="logo" width="130" height="130">
            <h2 class="form-signin-heading">Inventario<br> <small>Sistema de inventario</small></h2>
            <hr>
          </div>
          <div class="hero-unit">
            <form class="form-signin" action="index.php?step=1" method="POST">
              <h4 class="form-signin-heading">Iniciar Sesión</h4>
              <input id="dataUSU" name="USU" type="text" class="input-block-level" placeholder="Usuario">
              <input id="dataPAS" name="PAS" type="password" class="input-block-level" placeholder="Contrase&ntilde;a">
              <?php
           	  	$paso = htmlentities(isset($_GET["step"]) ? $_GET["step"] : NULL);
        	     	if($paso == 1) {
                  include ('php/inicio/procesoIniciar.php');
                }
           	  ?>
              <button class="btn btn-large btn-primary" type="submit">Ingresar</button>
            </form>

          </div>

        </div>
        <div class="span3"></div>
      </div>

    </div> <!-- /container -->

	<?php
	include ('layout/footer.php');
	?>

  </body>
</html>
