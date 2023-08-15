<?php	 include "php/conexion.php";

$orden="id";
if (isset($_GET["ORD"])) {
	$orden = $_GET["ORD"];
}

if (isset($_GET["USR"])) {
	// LLAMA A ASIGNAR Y VER ORGANISMOS
	include ('php/editor/usuario_organismos.php');
} else {

if (isset($_GET["UPD"])) {

	$id_usuario = $_GET["UPD"];

	$sql="SELECT u.id, u.id_rol, r.descripcion, u.usuario, u.nombre, u.apellido, u.email, u.clave
	      FROM usuarios AS u
				INNER JOIN roles r ON r.id = u.id_rol
				WHERE u.id = $id_usuario";

   $res = mysql_query($sql,$conex);

   if(mysql_num_rows($res) != 0) {

   	while ($usu = mysql_fetch_array($res)) {
			$id = $usu['id'];
			$usuario = $usu['usuario'];
			$VROL = $usu['id_rol'];
			$rol = $usu['descripcion'];
			$clave = $usu['clave'];
			$email = $usu['email'];
			$nombre = $usu['nombre'];
			$apellido = $usu['apellido'];
?>
   <div class="form-actions">
   	<form class="form-horizontal" id="frmcargo" action="php/editor/usuarioUPD.php" method="POST" enctype="multipart/form-data">

   	   <div class="input-prepend input-append" style="margin-left: 20px;">
				 	<div class="row" style="margin-top:5px;margin-left:60px;">
	   	      <span class="add-on">ID</span>
	    			<input class="span1" style="margin-right: 5px;" type="text" placeholder="ID" value="<?php echo $id ?>" disabled>
	    			<input class="span1" name="ID" style="display:none;" type="text" placeholder="ID" value="<?php echo $id ?>">
					</div>

					<div class="row" style="margin-top:5px;margin-left:60px;">

					 <div class="input-prepend input-append">
		    			<span class="add-on"><i class='icon-eye-open'></i></span>
							<select class="span2" name="ROL" title="Seleccione Rol">
								<?php
									// CARGO EL COMBO ROLES
									$sql = "SELECT id, descripcion FROM roles ORDER BY descripcion ASC";
									$result = mysql_query($sql,$conex);
									while ($dir = mysql_fetch_array($result)) {
										$id_rol=$dir['id'];
										$descripcion=$dir['descripcion'];
										if($VROL == $id_rol) {
											echo "<option value='".$id_rol."' selected>".$descripcion."</option>\n";
										} else {
											echo "<option value='".$id_rol."'>".$descripcion."</option>\n";
										}
									} // end while
								?>
							</select>
		    		</div>
		      	<div class="input-prepend input-append">
		    			<span class="add-on"><i class='icon-user'></i></span>
		    			<input class="span2" name="USU" type="text" placeholder="Usuario" value="<?php echo $usuario; ?>">
		    		</div>
						<div class="input-prepend input-append">
							<span class="add-on"><i class='icon-envelope'></i></span>
							<input class="span2" name="EMA" type="email" placeholder="Email" value="<?php echo $email; ?>">
						</div>

					</div>
					<div class="row" style="margin-top:5px;margin-left:60px;">

		      	<div class="input-prepend input-append">
		    			<span class="add-on"><i class='icon-user'></i></span>
		    			<input class="span2" name="NOM" type="text" placeholder="Nombre" value="<?php echo $nombre; ?>">
		    		</div>
		      	<div class="input-prepend input-append">
		    			<span class="add-on"><i class='icon-user'></i></span>
		    			<input class="span2" name="APE" type="text" placeholder="Apellido" value="<?php echo $apellido; ?>">
		    		</div>

		    		<div class="input-prepend input-append">
		    			<span class="add-on"><i class='icon-lock'></i></span>
		    			<input class="span2" name="PAS" type="password" placeholder="Clave" value="nueva">
		    		</div>

		      	<button type="submit" class="btn btn-primary">Modificar</button>
		    		<a href="editor.php?step=5" class="btn">Cancelar</a>

					</div>

				</div>

		</form>
    </div>

<?php
		}// fin del while
	}// fin del if

} elseif(isset($_GET["DEL"])) {

	$id_usuario = $_GET["DEL"];

	$sql="SELECT id, usuario FROM usuarios WHERE id=$id_usuario";

   $res = mysql_query($sql,$conex);

   if(mysql_num_rows($res) != 0) {

   	while ($usu = mysql_fetch_array($res)) {
			$id = $usu['id'];
			$usuario = $usu['usuario'];
		}// fin del while
	}// fin del if
?>

	<!-- Modal -->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
			<h3 id="myModalLabel">Baja usuario</h3>
		</div>
		<div class="modal-body">
			<p>¿Esta seguro de dar baja al usuario <?php echo $usuario; ?>?</p>
		</div>
		<div class="modal-footer">
			<a href='editor.php?step=5' class="btn" aria-hidden="true">Cancelar</a>
			<a href='php/editor/usuarioDEL.php?DEL=<?php echo $id; ?>' class="btn btn-primary">Baja</a>
		</div>
	</div>

<?php
} else {
?>
   <div class="form-actions">
   	<form class="form-horizontal" id="frmcargo" action="php/editor/usuarioINS.php" method="POST" enctype="multipart/form-data">

			<div class="row" style="margin-top:5px;margin-left:60px;">

			 <div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-eye-open'></i></span>
					<select class="span2" name="ROL" title="Seleccione Rol">
						<?php
							// CARGO EL COMBO ROLES
							$sql = "SELECT id, descripcion FROM roles ORDER BY descripcion ASC";
							$result = mysql_query($sql,$conex);
							while ($dir = mysql_fetch_array($result)) {
								$id_rol=$dir['id'];
								$descripcion=$dir['descripcion'];
								if($VROL == $id_rol) {
									echo "<option value='".$id_rol."' selected>".$descripcion."</option>\n";
								} else {
									echo "<option value='".$id_rol."'>".$descripcion."</option>\n";
								}
							} // end while
						?>
					</select>
    		</div>
      	<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-user'></i></span>
    			<input class="span2" name="USU" type="text" placeholder="Usuario">
    		</div>
				<div class="input-prepend input-append">
					<span class="add-on"><i class='icon-envelope'></i></span>
					<input class="span2" name="EMA" type="email" placeholder="Email">
				</div>

			</div>
			<div class="row" style="margin-top:5px;margin-left:60px;">

      	<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-user'></i></span>
    			<input class="span2" name="NOM" type="text" placeholder="Nombre">
    		</div>
      	<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-user'></i></span>
    			<input class="span2" name="APE" type="text" placeholder="Apellido">
    		</div>

    		<div class="input-prepend input-append">
    			<span class="add-on"><i class='icon-lock'></i></span>
    			<input class="span2" name="PAS" type="password" placeholder="Clave">
    		</div>
      	<button type="submit" class="btn btn-primary">Aceptar</a>
    		<button type="reset" class="btn">Nuevo</button>

			</div>

		</form>
		</div>
<?php
} // Fin del if
?>
	<div class="tablaEditor">
			<!-- Tabla -->
			<table class="table table-bordered table-striped table-hover">
				<thead>
              <tr>
              		<th></th>
                  <th><a href='editor.php?step=5&ORD=id'>ID</a></th>
									<th><a href='editor.php?step=5&ORD=descripcion'>Rol</th>
                  <th><a href='editor.php?step=5&ORD=usuario'>Usuario</th>
									<th><a href='editor.php?step=5&ORD=email'>Email</th>
									<th><a href='editor.php?step=5&ORD=nombre'>Nombre</th>
									<th><a href='editor.php?step=5&ORD=apellido'>Apellido</th>
									<th><a href='editor.php?step=5&ORD=log_insert'>Alta</th>
									<th><a href='editor.php?step=5&ORD=fecha_baja'>Baja</th>
              </tr>
            </thead>
          <tbody>

<?php

$sql="SELECT u.id, u.id_rol, r.descripcion, u.usuario, u.nombre, u.apellido, u.email, u.clave, u.fecha_baja, u.log_insert
			FROM usuarios AS u
			INNER JOIN roles r ON r.id = u.id_rol
			ORDER BY $orden ASC";

$result = mysql_query($sql,$conex);

if(mysql_num_rows($result) != 0) {
	while ($reg = mysql_fetch_array($result)) {
		$id=$reg['id'];
	  $usuario=$reg['usuario'];
	  $nombre=$reg['nombre'];
		$apellido=$reg['apellido'];
	  $email=$reg['email'];
	  $rol=$reg['descripcion'];
	  $baja=$reg['fecha_baja'];
	  $alta=$reg['log_insert'];

		if ($baja!="") {
			?><tr class='error'> <?php
		} else {
			?><tr> <?php
		}
?>
	<td>
   	<a href='editor.php?step=5&UPD=<?php echo $id; ?>' class='btn btn-primary' type='submit'>
   		<i class='icon-refresh icon-white'></i>
      </a>
      <a href='editor.php?step=5&DEL=<?php echo $id; ?>' class='btn btn-danger' type='submit'>
      	 	<i class='icon-trash icon-white'></i>
   		</a>
			<a href='editor.php?step=5&USR=<?php echo $id; ?>' class='btn btn-info' type='submit'>
					<i class='icon-plus-sign icon-white'></i> Unidades
			</a>
   </td>
   <td><?php echo $id; ?></td>
	 <td><?php echo $rol; ?></td>
   <td><?php echo $usuario; ?></td>
   <td><?php echo $email; ?></td>
	 <td><?php echo $nombre; ?></td>
	 <td><?php echo $apellido; ?></td>
	 <td><?php echo $alta; ?></td>
	 <td><?php echo $baja; ?></td>
 	 </tr>
<?php
	} // fin del while
} // fin del if

mysql_close($conex);
?>
			</tbody>
		</table>
	</div>

<?php } ?>
