<?php session_start(); ?>

<div class="masthead no-print">

  <div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <ul class="nav">


          <li>
            <a href="#" class="brand text-info">
              <img src="assets/imagen/logo/logo.png" alt="logo" width="30" height="30" style="margin-top:-5px;margin-bottom:-5px;">
              Inventario &nbsp;
            </a>
          </li>
          <li><a href="main.php"><i class="icon-home"></i>&nbsp;Inicio</a></li>

          <?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
          <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-th-large"></i>&nbsp;Administración <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="articulo.php">Artículos</a></li>
              <li><a href="familia.php">Familias y subfamilias</a></li>
              <li><a href="adjudicatario.php">Adjudicatarios</a></li>
            </ul>
          </li>
          <?php } ?>
          <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-random"></i>&nbsp;Distribución <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
              <li><a href="ingreso.php">Ingreso de items</a></li>
              <?php } ?>
              <li><a href="documento.php">Movimientos</a></li>
              <li><a href="oficina.php">Distribuci&oacute;n de inventario</a></li>
              <?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador') { ?>
              <li><a href="baja.php">Baja de artículo</a></li>
              <?php } ?>
            </ul>
          </li>

          <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-list"></i>&nbsp;Reportes <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario') { ?>
              <li><a href="reporte.php?step=3">Items ingresados (D01)</a></li>
              <li><a href="reporte.php?step=2">Hist&oacute;rico por art&iacute;culo</a></li>
              <?php } ?>
              <?php if($_SESSION['tipo']=='administrador' || $_SESSION['tipo']=='operador inventario' || $_SESSION['tipo']=='operador') { ?>
              <li><a href="reporte.php?step=1">Reporte art&iacute;culos por Unidad</a></li>
              <?php } ?>
            </ul>
          </li>

          <li class="divider-vertical"></li>
          <?php
          // Comprueba el estado de la sesion y la valida
          include ('php/online/procesoOnline.php');?>

        </ul>
      </div>
    </div>
  </div><!-- /.navbar -->
</div>
