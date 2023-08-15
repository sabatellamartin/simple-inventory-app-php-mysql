<head>

  <!-- TITULO DE LA PAGINA-->
  <title>Inventario</title>

  <!-- METAS -->
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="Martín Sabatella" >
  <meta charset="iso-8859-1">

  <!-- ICONO DE LA BARRA DE DIRECCIONES DEL NAVEGADOR -->
  <link href='assets/imagen/logo/logo.png' rel='shortcut icon' type='image/png'>

	<!-- Jquery -->
 	<script type="text/javascript" src="assets/jquery-1.9.0.min.js"></script>

	<script type='text/javascript' src='assets/bootstrap/js/bootstrap.js'></script>
	<script type='text/javascript' src='assets/bootstrap/js/bootstrap.min.js'></script>

	<link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

	<link href="assets/css/estilo.css" rel="stylesheet" type="text/css">

  <style>
    @media print {
        .no-print, .no-print * {
            display: none !important;
        }
    }
  </style>

  <script type="text/javascript" >
    $(document).ready(function(){
      $('#myModal').modal('show');
    });
  	$(document).ready(function(){
  		$("#modal").click(function(){
  			$('#myModal').modal();
  			return false;
  		});
  		$('#menu-usuario').dropdown();
  		$('.dropdown-toggle').dropdown();
  	});

    function habilitarCodigo() {
      let cantidad = document.getElementById("cantidadItem").value;
      document.getElementById("cantidadItem").value = cantidad < 1 ? 1 : cantidad;
      document.getElementById("codigoItem").disabled = cantidad == 1 ? false : true;
    }

    function habilitarProcedimiento() {
      let x = document.getElementById("checkProcedimiento");
      document.getElementById("tipoProcedimiento").disabled = !x.checked;
      document.getElementById("nroProcedimiento").disabled = !x.checked;
      document.getElementById("adjudicatarioProcedimiento").disabled = !x.checked;
      document.getElementById("nroFacturaProcedimiento").disabled = !x.checked;
      document.getElementById("garantiaProcedimiento").disabled = !x.checked;
      document.getElementById("plazoProcedimiento").disabled = !x.checked;
    }

    function habilitarSubfamilias() {
      let x = document.getElementById("checkSubfamilia");
      document.getElementById("comboSubfamilia").disabled = !x.checked;
    }

    function resetBuscar() {
        document.getElementById("form").reset();
    }
  </script>

</head>
