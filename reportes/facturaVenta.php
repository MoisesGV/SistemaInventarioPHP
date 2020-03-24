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
	if ($_SESSION['ventas']==1)
	{

		require 'Factura.php';

	//ESTABLECEMOS  LOS DATOS DE LA EMPRESA
		$logo = 'logo.png';
		$ext_logo = 'png';
		$empresa = 'CVV CAJA VENEZOLANA DE VALORES, C.A';
		$documento = 'J-30018793-4';
		$direccion = 'El Rosal, Caracas';
		$telefono = '0212-9526277';
		$email = 'CVV@CAJAVENEZOLANA.COM';

	//OBTENEMOS LOS DATOS DE LA CABECERA DE LA VENTA ACTUAL
		require_once '../modelos/Ventas.php';
		$ventas = new Ventas();
		$resV = $ventas->CabeceraFacturaVenta($_GET['id']); 

	//RECORREMOS TODOS LOS VALORES OBTENIDOS
		$regV = $resV->fetch_object();

	//ESTABLECEMOS LA CONFIGURACION DE LA FACTURA
		$pdf = new PDF_Invoice( 'P','mm','A4' );
		$pdf->AddPage();

	//ENVIAMOS LOS DATOS DE LA EMPRESA QUE DEFINIMOS AL INICIO
		$pdf->addSociete( utf8_decode($empresa), $documento."\n" .
			utf8_decode("Direccion: ").utf8_decode($direccion)."\n".
			utf8_decode("Teléfono: ").$telefono."\n".
			utf8_decode("Email: ").$email, $logo, $ext_logo );

		$pdf->fact_dev( "$regV->tipo_comprobante ", "$regV->serie_comprobante-$regV->num_comprobante" );

	//MARCA DE AGUA EN LA FACTURA, EN ESTE CASO LA ENVIAMOS VACIA
		$pdf->temporaire( "" );

	//AGREGAMOS LA FECHA DE LA FACTURA
		$pdf->addDate( $regV->fecha );

	//AGREGAMOS LOS DATOS DEL CLIENTE
		$pdf->addClientAdresse(utf8_decode($regV->cliente),"Domicilio: ".utf8_decode($regV->direccion),$regV->tipo_documento."-".$regV->num_documento,"Email :".utf8_decode($regV->email),utf8_decode("Teléfono: ").$regV->telefono);

	//ESTABLECEMOS LAS COLUMNAS QUE VA A TENER EL LISTADO DEL DETALLE DE LA VENTA
		$cols = array( "CODIGO" 	 	 => 23,
					   "DESCRIPCION" 	 => 78,
					   "CANTIDAD" 	 	 => 21,
					   "PRECIO U." 		 => 22,
					   "DESCUENTO" 	 	 => 24,
					   "SUBTOTAL"		 => 22 );
		$pdf->addCols($cols);

		$cols = array( "CODIGO"   		 => "L",
					   "DESCRIPCION"	 => "L",
					   "CANTIDAD" 		 => "C",
					   "PRECIO U." 		 => "R",
					   "DESCUENTO" 		 => "R",
					   "SUBTOTAL" 		 => "C" );
		$pdf->addLineFormat($cols);
		$pdf->addLineFormat($cols);

	//ACTUALIZAMOS EL VALOR DE LA COORDENADA "Y", QUE SERÁ LA UBICACION DESDE DONDE EMPEZAREMOS A MOSTRAR LOS DATOS
		$y = 89;

	//AGREGAMOS TODOS LOS DETALLES DE LA VENTA ACTUAL
		$resDV = $ventas->DetalleFacturaVenta($_GET["id"]);
		while( $regDV = $resDV->fetch_object() )
		{ 
			$line = array( "CODIGO" 	 	 => "$regDV->codigo",
					  	   "DESCRIPCION" 	 => utf8_decode("$regDV->articulo"),
					   	   "CANTIDAD" 	 	 => "$regDV->cantidad",
					       "PRECIO U."       => "$regDV->precio_venta",
					       "DESCUENTO" 	 	 => "$regDV->descuento",
					       "SUBTOTAL"		 => "$regDV->subtotal" );
			$size = $pdf->addLine( $y, $line);
			$y += $size + 2; 
		}

	//CONVERTIMOS EL TOTAL EN LETRAS
		require_once 'Letras.php';
		$Letras = new EnLetras();
		$totalEnLetras =strtoupper($Letras->ValorEnLetras($regV->total_venta, "BS "));
		$pdf->addCadreTVAs("--".$totalEnLetras);

	//MOSTRAMOS EL IMPUESTO
		$pdf->addTVAs( $regV->impuesto, $regV->total_venta, "Bs ");
		$pdf->addCadreEurosFrancs("IVA"." $regV->impuesto %");
		$pdf->Output('Reporte de Venta', 'I');

	}
	else
	{
		echo "No tiene permiso para visualizar el Reporte";
	}

}

ob_end_flush();
?>
