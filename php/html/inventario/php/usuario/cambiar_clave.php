<?php if ($login==true) { ?>

<div class="row">
	<div class="col-8">
		<h3 class="muted text-left" style="margin-left:20px;">Cambiar contraseña</h3>
	</div>
	<div class="col-4">
    <a class='btn' href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Atr&aacute;s" style="margin-top:-40px;margin-bottom:10px;float:right;"><i class='icon-arrow-left'></i>&nbsp;&nbsp;Atr&aacute;s</a>
	</div>
</div>

<?php if (isset($_GET["ERR"])){
	$error = $_GET["ERR"]; ?>
	<div class="alert alert-danger">
	<strong>Error!</strong> <?php echo $error ?>
</div>
<?php }?>

<!-- Jumbotron -->
<div class="form-actions">
  <form id="form" class="form-inline" action="php/usuario/UPD_clave.php" method="POST" enctype="multipart/form-data">
		<fieldset id="frm">
		<div style="margin-left: 80px; padding-left: -10; padding: 10px;">
		<input name='OLD' class='span4' type='password' value='' placeholder='Contraseña actual' required>
		<br></br>
		<input name='NEW' class='span4' type='password' value='' placeholder='Ingresa nueva constraseña' required>
    <br></br>
    <input name='REP' class='span4' type='password' value='' placeholder='Repita nueva constraseña' required>
		<br></br>
		<button class="btn btn-primary" type="submit"><i class="icon-ok icon-white"></i>&nbsp;&nbsp;Cambiar</button>
    <button class="btn" type="button" onclick="resetBuscar()" value="Limpiar"><i class="icon-refresh"></i>&nbsp;&nbsp;Limpiar</button>
	</div>
 	</fieldset>
	</form>
</div>

<?php } ?>
