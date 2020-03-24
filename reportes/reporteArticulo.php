<?php
//ACTIVAMOS ALMACENAMIENTO EN EL BUFFER
ob_start();
//SI LA SESION NO HA SIDO INICIADA INICIAMOS SESION
	if (strlen(session_id()) < 1) 
    session_start();

	if (!isset($_SESSION["nombre"])) {
		//echo "Debe Ingresar al Sistema Correctamente para visualizar el reporte";
		header("Location: ../vistas/login.html");
	}
	else
	{
 
	  //VALIDAMOS QUE EL USUARIO TENGA ACCESO AL MÓDULO CATEGORIA
		if ($_SESSION['almacen']==1) {

		//INCLUIMOS LA CLASE FPDF TABLE
			require 'mc_table.php';

		//INSTANCIAMOS LA CLASE PDF_MC_Table PARA GENERAR EL DOCUMENTO PDF
			$pdf = new PDF_MC_Table();

		//AGREGAMOS LA PRIMERA PAG DEL DOCUMENTO PDF
			$pdf->AddPage();
		
		//INDICAMOS EL INICIO DEL MARGEN SUPERIOR EN 25PX
			$y_axis_initial = 25;

		//INDICAMOS EL TIPO DE LETRA Y CREAMOS EL TITULO DE LA PÁGINA
			$pdf->SetFont('Arial', 'B', 12);
			$pdf->Cell(40,6,'',0,0,'C');
			$pdf->Cell(100,6,'LISTA DE ARTICULOS',1,0,'C');
			$pdf->Ln(10);

		//INDICAMOS LAS CELDAS PARA LOS TITULOS DE CADA COLUMNA Y LE ASIGNAMOS UN FONDO GRIS Y EL TIPO DE LETRA
			$pdf->SetFillColor(232,232,232);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(58,6,'Nombre',1,0,'C',1);
			$pdf->Cell(50,6,utf8_decode('Categoría'),1,0,'C',1);
			$pdf->Cell(30,6,utf8_decode('Código'),1,0,'C',1);
			$pdf->Cell(12,6,'Stock',1,0,'C',1);
			$pdf->Cell(35,6,utf8_decode('Descripción'),1,0,'C',1);
			$pdf->Ln(10);

		//CREAMOS LAS FILAS DE LOS REGISTROS SEGUN LA CONSULA SQL
			require_once '../modelos/Articulo.php';
			
			$articulo = New Articulo();
			$res = $articulo->ListarArticulo();

		//INDICAMOS LAS CELDAS DE LA TABLA CON LOS REGISTRO QUE VAMOS A MOSTRAR
			$pdf->SetWidths(array(58,50,30,12,35));

			while ($reg = $res->fetch_object()) {
				
				$nombre = $reg->nombre;
				$categoria = $reg->Categoria;
				$codigo = $reg->codigo;
				$stock = $reg->stock;
				$descripcion = $reg->descripcion;

				$pdf->SetFont('Arial','',10);
				$pdf->Row(array(utf8_decode($nombre),utf8_decode($categoria),$codigo,$stock, utf8_decode($descripcion)));
			}

		//MOSTRAMOS EL DOCUMENTO PDF
			$pdf->Output();
		}
  	else
  	{
    	echo "No tiene permiso para visualizar el Reporte";
  	}

  } 
  ob_end_flush();
?>