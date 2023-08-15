<?php if($step=='') { ?>
			<h3 class="muted text-left">Estados de documentos</h3>
<?php } elseif($step=='1') { ?>
			<h3 class="muted text-left">Unidades</h3>
<?php } elseif($step=='2') { ?>
			<h3 class="muted text-left">Familias</h3>
<?php } elseif($step=='3') { ?>
			<h3 class="muted text-left">Administración de Tipos de documento</h3>
<?php } elseif($step=='4') { ?>
			<h3 class="muted text-left">Administración de roles</h3>
<?php } elseif($step=='5') { ?>
			<h3 class="muted text-left">Administración de usuarios</h3>
<?php } elseif($step=='6') { ?>
			<h3 class="muted text-left">Administración de Tipos de procedimientos</h3>
<?php } elseif($step=='7') { ?>
			<h3 class="muted text-left">Motivos de baja de items</h3>
<?php } ?>

 <div id="btn-editor" class="btn-group">
  	 	<button class="btn">Administración</button>
 	<button class="btn dropdown-toggle" data-toggle="dropdown">
 		<span class="caret"></span>
 	</button>
 	<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
<?php if($step=='') { ?>
 			<li class="active"><a tabindex="-1" href="editor.php">Estados de documento</a></li>
<?php } else {  ?>
 			<li><a tabindex="-1" href="editor.php">Estados de documento</a></li>
<?php } ?>

<?php if($step=='1') { ?>
			<li class="active"><a tabindex="-1" href="editor.php?step=1">Unidades</a></li>
<?php } else {  ?>
 			<li><a tabindex="-1" href="editor.php?step=1">Unidades</a></li>
<?php } ?>

<?php if($step=='2') { ?>
			<li class="active"><a tabindex="-1" href="editor.php?step=2">Familias</a></li>
<?php } else {  ?>
 			<li><a tabindex="-1" href="editor.php?step=2">Familias</a></li>
<?php } ?>

<?php if($step=='3') { ?>
			<li class="active"><a tabindex="-1" href="editor.php?step=3">Tipos de documento</a></li>
<?php } else {  ?>
 			<li><a tabindex="-1" href="editor.php?step=3">Tipos de documento</a></li>
<?php } ?>

<?php if($step=='4') { ?>
			<li class="active"><a tabindex="-1" href="editor.php?step=4">Roles</a></li>
<?php } else {  ?>
 			<li><a tabindex="-1" href="editor.php?step=4">Roles</a></li>
<?php } ?>

<?php if($step=='5') { ?>
			<li class="active"><a tabindex="-1" href="editor.php?step=5">Usuarios</a></li>
<?php } else {  ?>
 			<li><a tabindex="-1" href="editor.php?step=5">Usuarios</a></li>
<?php } ?>

<?php if($step=='6') { ?>
			<li class="active"><a tabindex="-1" href="editor.php?step=6">Tipos de procedimientos</a></li>
<?php } else {  ?>
 			<li><a tabindex="-1" href="editor.php?step=6">Tipos de procedimientos</a></li>
<?php } ?>

<?php if($step=='7') { ?>
			<li class="active"><a tabindex="-1" href="editor.php?step=7">Motivos de baja</a></li>
<?php } else {  ?>
 			<li><a tabindex="-1" href="editor.php?step=7">Motivos de baja</a></li>
<?php } ?>

	</ul>
</div>

<hr>
