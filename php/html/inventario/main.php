<?php session_start();?>
<!DOCTYPE HTML>
<html>
<?php include ('layout/header.php'); ?>
<body>
  <div class="container">
    <?php include ('layout/navbar.php'); ?>

	<?php if ($login==true) { ?>

      <div class="container-fluid">
        <div class="row-fluid">
          <div class="span4">
            <!--Sidebar content-->
            <?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
            <h4>Administrar</h4>
            <ul class="nav nav-tabs nav-stacked">
              <li><a href="articulo.php">Artículos</a></li>
              <li><a href="familia.php">Familias y subfamilias</a></li>
              <li><a href="adjudicatario.php">Adjudicatarios</a></li>
            </ul>
            <?php } ?>
            <h4>Distribución</h4>
            <ul class="nav nav-tabs nav-stacked">
              <?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
              <li><a href="ingreso.php">Ingreso de items</a></li>
              <?php } ?>
              <li><a href="documento.php">Movimientos</a></li>
              <li><a href="oficina.php">Distribuci&oacute;n de inventario</a></li>
              <?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador') { ?>
              <li><a href="baja.php">Baja de artículo</a></li>
              <?php } ?>
            </ul>
            <h4>Reportes</h4>
            <ul class="nav nav-tabs nav-stacked">
              <?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
              <li><a href="reporte.php?step=3">Items ingresados</a></li>
              <li><a href="reporte.php?step=2">Hist&oacute;rico por art&iacute;culo</a></li>
              <?php } ?>
              <?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario' || $_SESSION['tipo']=='operador') { ?>
              <li><a href="reporte.php?step=1">Reporte art&iacute;culos por Unidad</a></li>
              <?php } ?>
            </ul>
          </div>
          <div class="span8">
            <div>
              <h3>Sistema de Inventario</h3>
              <h4 class="muted"></h4>
            </div>
            <hr>
            <!--Body content-->
            <p class="muted">
            Bienvenido/a <?php echo $_SESSION['usuario']; ?> al sistema de gestión de inventario.
            </p>
            <p class="muted">
            Este software permite a sus usuarios administrar los bienes de uso,
            para ello se mantiene información sobre adjudicatarios, art&iacute;culos, oficinas y movimientos de bienes muebles.
            Luego con esta información es posible obtener reportes personalizados que ayuden al proceso de administración.
            </p>
            <p class="muted">
            contacto.
            </p>
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-md-4">

        </div>
        <div class="col-md-4">

        </div>
        <div class="col-md-4"></div>
      </div>

      <ul class="dropdown-menu">
        <?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
        <li><a href="reporte.php?step=1">Reporte art&iacute;culos por unidad</a></li>
        <?php } ?>
        <?php if($_SESSION['tipo']=='autorizador' || $_SESSION['tipo']=='operador oficina') { ?>
        <li><a href="reporte.php?step=2">Reporte art&iacute;culos por oficina</a></li>
        <?php } ?>
        <!--<li class='divider'></li>
        <li><a href="#">Reporte cantidad por art&iacute;culo por unidad</a></li>-->
      </ul>

  <?php } else { ?>
		<script type="text/javascript">setTimeout(function(){window.location="index.php"},0);</script>
	<?php } ?>

  <hr>

  <?php include ('layout/footer.php'); ?>
  </div> <!-- /container -->
</body>
</html>
