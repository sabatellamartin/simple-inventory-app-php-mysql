<h3 class="muted text-left">Auditoria</h3>
<hr>

<?php
include "php/conexion.php";

// CANTDAD DE REGISTROS POR PAGINA
$cant_reg = 9;

if (isset($_GET["PAG"])) {
  $num_pag = $_GET["PAG"];
  $comienzo = ($num_pag - 1) * $cant_reg;
} else {
	$comienzo = 0;
	// ASIGNO LA PAGINA EN 1 COMO LA PRIMERA
	$num_pag = 1;
}

if (isset($_GET["ORD"])) {
	$VORDEN = $_GET["ORD"];
} else {
	$VORDEN = "id";
}

if (isset($_GET["FUN"])) {
	$VFUNCIONALIDAD = $_GET["FUN"];
} else {
	$VFUNCIONALIDAD = "";
}

if (isset($_GET["OBS"])) {
	$VOBSERVACION = $_GET["OBS"];
} else {
	$VOBSERVACION = "";
}

if (isset($_GET["USU"])) {
	$VUSUARIO = $_GET["USU"];
} else {
	$VUSUARIO = "";
}

if (isset($_GET["FE"])) {
	$VFECHA = $_GET["FE"];
} else {
	$VFECHA = "";
}

$parametro = "&USU=".$VUSUARIO."&FUN=".$VFUNCIONALIDAD."&OBS=".$VOBSERVACION."&FE=".$VFECHA."";

?>

  <!-- Jumbotron -->
  <div class="jumbotron">
    <form id="form" action="auditoria.php" method="GET">
 		<fieldset id="frm">
      <input id='dataFUN' name='FUN' class='input-small' type='text' value='<?php echo $VFUNCIONALIDAD; ?>' placeholder='Funcionalidad'>
      <input id='dataOBS' name='OBS' class='input-small' type='text' value='<?php echo $VOBSERVACION; ?>' placeholder='Observación'>
			<input id='dataUSU' name='USU' class='input-small' type='text' value='<?php echo $VUSUARIO; ?>' placeholder='Usuario'>
			<input id='dataFE' name='FE' class='input-small' type='text' value='<?php echo $VFECHA; ?>' placeholder='Fecha'>
	  	<button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i>&nbsp;&nbsp;Buscar</button>
   	</fieldset>
  	</form>
  </div>

  <hr>

  <div class="tabla">

	<table class="table table-bordered table-striped table-hover">
		<thead>
          <tr>
          <?php $parametro = "&USU=".$VUSUARIO."&FUN=".$VFUNCIONALIDAD."&OBS=".$VOBSERVACION."&FE=".$VFECHA.""; ?>
              <th>ID</th>
              <th><a href='auditoria.php?ORD=funcionalidad<?php echo $parametro;?>'>Funcionalidad</th>
              <th><a href='auditoria.php?ORD=observacion<?php echo $parametro;?>'>Observacion</th>
              <th><a href='auditoria.php?ORD=log_usuario<?php echo $parametro;?>'>UsuarioID</th>
              <th><a href='auditoria.php?ORD=usuario<?php echo $parametro;?>'>Usuario</th>
              <th><a href='auditoria.php?ORD=log_insert<?php echo $parametro;?>'>Fecha</th>
          </tr>
        </thead>

        <tbody>

			<?php

			$total_registros = 0;

			$sql="SELECT COUNT(id) FROM auditorias";
    	$result = mysql_query($sql,$conex);
    	if(mysql_num_rows($result) != 0) {
    		while ($reg = mysql_fetch_array($result)) {
    			$total_registros= $reg['COUNT(id)'];
    		}
    	}

	 		$sql="SELECT
	 				aud.id,
	 				aud.funcionalidad,
	 				aud.observacion,
	 				aud.log_usuario,
	 				aud.log_insert,
	 				usu.usuario
					FROM auditorias AS aud
					LEFT JOIN usuarios usu ON aud.log_usuario = usu.id
					WHERE aud.log_insert LIKE '%".$VFECHA."%'
					AND aud.observacion LIKE '%".$VOBSERVACION."%'
					AND aud.funcionalidad LIKE '%".$VFUNCIONALIDAD."%' ";
			if ($VUSUARIO!='') {
				$sql .= "AND usu.usuario LIKE '%".$VUSUARIO."%' ";
			}
			$sql .= "ORDER BY ".$VORDEN." DESC ";
			$sql .= "LIMIT ".$comienzo.",".$cant_reg."";

    	$result = mysql_query($sql,$conex);
    	if(mysql_num_rows($result) != 0) {
    		while ($reg = mysql_fetch_array($result)) {
    			$id=$reg['id'];
    			$funcionalidad=$reg['funcionalidad'];
					$observacion=$reg['observacion'];
					$log_usuario=$reg['log_usuario'];
					$usuario=$reg['usuario'];
					$fecha=$reg['log_insert'];

?>
				<tr>
					<td><?php echo $id; ?></td>
        	<td><?php echo $funcionalidad; ?></td>
          <td><?php echo $observacion; ?></td>
         	<td><?php echo $log_usuario; ?></td>
         	<td><?php echo $usuario; ?></td>
         	<td><?php echo $fecha; ?></td>
        </tr>
					<?php
		        	}
	        	}
						mysql_close($conex);
					?>
		</tbody>
	</table>
</div>


<?php
// CALCULO EL TOTAL DE PAGINAS (TODOS LOS REGISTROS / 10)
$total_paginas = ceil($total_registros / $cant_reg);

echo "<div class='pagination  pagination-centered'><ul>";

// SI EL NUMERO DE PAGINA -1 ES > 0
if(($num_pag - 1) > 0) {
	// ETONCES NO ES LA PRIMERA VEZ QUE LISTAMOS, CREAMOS EL VINCULO ATRAS
	echo "<li><a href='auditoria.php?PAG=".($num_pag - 1)."".$parametro."'>Anterior</a></li>";

} else {
	echo " <li class='disabled'><a href='#'>&laquo;</a></li>";
}

if ($total_paginas < 5) {
	$pag_ant=1;
	$pag_max=$total_paginas;
} else {
	// SI ESTAMOS EN LA PRIMERA PAGINA
	if ($num_pag == 1) {
		// ENTONCES LA PAGINA ANTERIOR NO EXISTE, ES 1.
		$pag_ant=$num_pag;
		// LA PAGINA MAXIMA ES 1+3
		$pag_max=($num_pag+3);
	} else if ( $num_pag == ($total_paginas-1) ) {
		$pag_ant=($num_pag-2);
		$pag_max=($num_pag+1);
	} else if ( $num_pag == $total_paginas ) {
		$pag_ant=($num_pag-3);
		$pag_max=$num_pag;
	} else {
		$pag_ant=($num_pag-1);
		$pag_max=($num_pag+2);
	}
}

// LISTAMOS LOS VINCULOS CON SUS RESPECIVAS PAGINAS
for ($i=$pag_ant; $i<=$pag_max; $i++) {
	// SI EL NUMERO DE PAGINA ES EL ACTUAL
	if ($num_pag == $i) {
		// MOSTRAMOS PAGINA ACTUAL
		echo "<li class='active'><a href='#'>".$num_pag."</a></li>";
	// DE LO CONTRARIO
	} else {
		// CREAMOS EL VINULO DE LA PAGINA
		echo "<li><a href='auditoria.php?PAG=".$i."".$parametro."'>$i</a></li>";
	}
}
// HACEMOS EL LINK PARA SIGUIENTE
if(($num_pag + 1)<=$total_paginas) {
	echo "<li><a href='auditoria.php?PAG=".($num_pag+1)."".$parametro."'>Siguiente</a></li>";
} else {
	echo "<li class='disabled'><a href='#'>&raquo;</a></li>";
}

echo "</ul></div>";

?>
