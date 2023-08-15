<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */


 session_start();

 $usuario = $_SESSION['usuario'];
/*
 if ($_SESSION['tipo']!='administrador' || $_SESSION['tipo']!='operador inventario') {
   exit;
 } // FIN IF PERMISOS
*/

  if (isset($_GET["ORG"])) {
  	$ORGANISMO = $_GET["ORG"];
  } else {
  	$ORGANISMO = "";
  }
  if (isset($_GET["INI"])) {
  	$INICIO = $_GET["INI"];
  } else {
  	$INICIO = "";
  }
  if (isset($_GET["FIN"])) {
  	$FIN = $_GET["FIN"];
  } else {
  	$FIN = "";
  }
  if (isset($_GET["ORD"])) {
  	$ORDEN = $_GET["ORD"];
  } else {
  	$ORDEN = "f.nombre";
  }

  // CARGO OPCIONES LOS ORGANISMOS DEL USUARIO LOGEADO
  function getOrganismo($organismo) {
  	include "../conexion_new.php";
  	$sql = "SELECT o.id, o.nombre, o.sigla
  					FROM organismos AS o
            WHERE o.id = $organismo";
    $result = mysqli_query($connection, $sql);
    mysqli_close($connection);
    return $result;
  }

  // OBTENGO EL DETALLE DEL DOCUMENTO
  function getDetalle($organismo, $inicio, $fin, $orden) {
  	include "../conexion_new.php";

    	$sql = "SELECT
    		a.id,
    		f.nombre AS familia,
        f.descripcion AS familia_descripcion,
    		a.nombre AS articulo_nombre,
    		a.descripcion AS articulo_descripcion,
    		SUM(CASE WHEN d.id_organismo_receptor = '$organismo' THEN dd.cantidad ELSE 0 END) AS entran,
    		SUM(CASE WHEN d.id_organismo_emisor = '$organismo' THEN dd.cantidad ELSE 0 END) AS salen,
    		SUM(CASE WHEN d.id_organismo_receptor = '$organismo' THEN dd.cantidad ELSE 0 END) - SUM(CASE WHEN d.id_organismo_emisor = '$organismo' THEN dd.cantidad ELSE 0 END) AS total,
    		orge.nombre AS emisor,
    		orgr.nombre AS receptor,
    		a.fecha_baja AS articulo_baja
    		FROM movimientos AS m
    		INNER JOIN documentos d ON d.id = m.id_documento
    		INNER JOIN detalles_documento dd ON dd.id = m.id_detalle
    		INNER JOIN articulos a ON a.id = dd.id_articulo
    		INNER JOIN familias f ON f.id = a.id_familia
    		INNER JOIN organismos orge ON orge.id = d.id_organismo_emisor
    		INNER JOIN organismos orgr ON orgr.id = d.id_organismo_receptor
    		WHERE d.id_organismo_receptor = $organismo
    		OR d.id_organismo_emisor = $organismo ";
     if ($inicio != "" && $fin != "") {
    	 $sql .= "AND m.fecha BETWEEN '$inicio' AND '$fin' ";
     } else if ($inicio != "" && $fin == "") {
    	 $sql .= "AND m.fecha >= '$inicio' ";
     } else if ($inicio == "" && $fin != "") {
    	 $sql .= "AND m.fecha <= '$fin' ";
     }
     $sql .= "GROUP BY dd.id_articulo";

    $result = mysqli_query($connection, $sql);
    mysqli_close($connection);
    return $result;
 }


 /** Error reporting */
 error_reporting(E_ALL);
 ini_set('display_errors', TRUE);
 ini_set('display_startup_errors', TRUE);
 date_default_timezone_set('Europe/London');

 if (PHP_SAPI == 'cli')
 	die('This example should only be run from a Web Browser');

 /** Include PHPExcel */
 require_once dirname(__FILE__) . '/../../lib/PHPExcel-1.8/Classes/PHPExcel.php';

 // Create new PHPExcel object
 $objPHPExcel = new PHPExcel();

 // Set document properties
 $objPHPExcel->getProperties()->setCreator($usuario)
 							 ->setLastModifiedBy($usuario)
 							 ->setTitle("Office 2007 XLSX Test Document")
 							 ->setSubject("Office 2007 XLSX Test Document")
 							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
 							 ->setKeywords("office 2007 openxml php")
 							 ->setCategory("Test result file");

  $org = getOrganismo($ORGANISMO);
  if(mysqli_num_rows($org) > 0) {
   while ($row = mysqli_fetch_array($org)) {
     // Add some data
     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('B2', $row[2])
                 ->setCellValue('C2', $row[1]);
   }
  }

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('E2', 'Desde')
              ->setCellValue('F2', $INICIO)
              ->setCellValue('H2', 'Hasta')
              ->setCellValue('I2', $FIN);

  // Formato texto en negrita para organismo
  $objPHPExcel->getActiveSheet()->getStyle("B2:I2")->getFont()->setBold(true);

  // Cabezal de la tabla
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('B4', '#')
              ->setCellValue('C4', 'Familia')
              ->setCellValue('D4', 'Familia Descripción')
              ->setCellValue('E4', 'Artículo')
              ->setCellValue('F4', 'Descripción')
              ->setCellValue('G4', 'Entregados')
              ->setCellValue('H4', 'Devueltos')
              ->setCellValue('I4', 'Cantidad');
  // Formato texto en negrita para el cabezal
  $objPHPExcel->getActiveSheet()->getStyle("B4:I4")->getFont()->setBold(true);

  // Imprimo fila a fila la información
  $i = 6;
  $detalle = getDetalle($ORGANISMO, $INICIO, $FIN, $ORDEN);
  if(mysqli_num_rows($detalle) > 0) {
   while ($linea = mysqli_fetch_array($detalle)) {
     // Add some data
     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('B' . $i, $linea[0])
                 ->setCellValue('C' . $i, $linea[1])
                 ->setCellValue('D' . $i, $linea[2])
                 ->setCellValue('E' . $i, $linea[3])
                 ->setCellValue('F' . $i, $linea[4])
                 ->setCellValue('G' . $i, $linea[5])
                 ->setCellValue('H' . $i, $linea[6])
                 ->setCellValue('I' . $i, $linea[7]);
     $i += 1;
   }
 }

 // Ajusto las dimensiones de la columna
 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
 $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
 $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
 $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
 $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
 $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

 // Rename worksheet
 $objPHPExcel->getActiveSheet()->setTitle('Simple');


 // Set active sheet index to the first sheet, so Excel opens this as the first sheet
 $objPHPExcel->setActiveSheetIndex(0);


 // Redirect output to a client’s web browser (Excel2007)
 header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
 header('Content-Disposition: attachment;filename="itemsXorganismo.xlsx"');
 header('Cache-Control: max-age=0');
 // If you're serving to IE 9, then the following may be needed
 header('Cache-Control: max-age=1');

 // If you're serving to IE over SSL, then the following may be needed
 header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
 header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
 header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
 header ('Pragma: public'); // HTTP/1.0

 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
 $objWriter->save('php://output');
 exit;
