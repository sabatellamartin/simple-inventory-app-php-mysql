<?php
// set time-out period (in seconds)
$inactive = 60*60*6;//6 horas

// check to see if $_SESSION["timeout"] is set
if (isset($_SESSION["timeout"])) {
    // calculate the session's "time to live"
    $sessionTTL = time() - $_SESSION["timeout"];
    if ($sessionTTL > $inactive) {
        session_destroy();
        // Redirijo al Index
        header("Location: /index.php");
    }
}

$_SESSION["timeout"] = time();

// CONECAR AL SERVIDOR
include("php/conexion.php");

$login = false;

// COMPROBAMOS QUE EXISTAN LAS VARIABLES DE SESION
if(isset($_SESSION['sesion_usuario']) && isset($_SESSION['id_usuario']))  {
	// OBTENEMOS CONTENIDO DE LAS VARIABLES
	$sesion 	= htmlentities($_SESSION['sesion_usuario'], ENT_QUOTES);
	$id_usuario 	= htmlentities($_SESSION['id_usuario'], ENT_QUOTES);

	// BUSCA USUARIO CON LA ID DE LA VARIBLE
	$sql = "SELECT id, usuario, clave FROM usuarios WHERE id='$id_usuario'";

	// EJECUTAR SENTENCIA SQL
	$result = mysql_query($sql,$conex);

   	// SI EL NUMERO DE FILAS DE LA CONSULTA ES DISTINTO DE 0, ENTONCES EXISTE.
   	if(mysql_num_rows($result) != 0) {

      	// OBTENEMOS LA INFORMACION DEL USUARIO.
      	while($row = mysql_fetch_array($result)) {
        	// COMPROBAMOS LOS DATOS DE LA 'session' COINSIDAN CON LOS DATOS GUARDADOS.

        	if(md5($row['usuario'] . $row['clave']) == $sesion) {
          		// DEVOLVEMOS TRUE, USUARIO LOGEADO
          		$login = true;
          		$id	= $row['id'];
          		$usuario = $row['usuario'];
              ?>

              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class='icon-user'></i>&nbsp;<?php echo "".$usuario.""; ?> <b class="caret"></b></a>
                <ul class='dropdown-menu'>
                  <li><a href='usuario.php'><i class='icon-lock'></i> Cambiar Contraseña</a></li>
                  <?php if($_SESSION['tipo']=='administrador') { ?>
                    <li><a href='editor.php'><i class='icon-pencil'></i> Administración</a></li>
             			<?php }?>
                  <?php if($_SESSION['tipo']=='administrador') { ?>
                    <li><a href='auditoria.php'><i class='icon-eye-open'></i> Auditoria</a></li>
                    <li class='divider'></li>
             			<?php }?>
                  <li><a href='php/inicio/cerrarSesion.php'><i class='icon-remove-circle'></i> Salir</a></li>
        				</ul>
              </li>

        <?php
        } else	{
				      $login = false;
 		    }
      } // FIN DEL WHILE
    } else {
		    $login = false;
 	  }
  } else {
	   $login = false;
  }
	// CERRAR CONEXION
	mysql_close($conex);
?>
