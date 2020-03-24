<?php
require_once "../modelos/Consultas.php";

$consultas = new Consultas();

switch ($_GET["op"]) {

	case 'comprasFecha':
		
		$fechaInicio=$_REQUEST["fechaInicio"];
		$fechaFin=$_REQUEST["fechaFin"];
		$res = $consultas->ConsultasCompras($fechaInicio, $fechaFin);

		$data = array();

		while ( $reg=$res->fetch_object() ) {
			$data[] = array(
				
				"0" => $reg->fecha,
				"1" => $reg->usuario,
				"2" => $reg->proveedor,
				"3" => $reg->tipo_comprobante,
				"4" => $reg->serie_comprobante.' '.$reg->num_comprobante,
				"5" => $reg->total_compra,
				"6" => $reg->impuesto,
				"7" => ($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
										 '<span class="label bg-red">Anulado</span>'
			);
		}
		$resultados = array( 
			"sEcho"=>1, //INFORMACION PARA EL DATATABLES
			"iTotalRecords" => count($data), //ENVIAMOS EL TOTAL DE REGISTROS AL DATATABLE
			"iTotalDisplayRecords" => count($data), //TOTAL DE REGISTROS A VISUALIZAR
			"aaData" => $data
		);
		echo json_encode($resultados);
		break;

	case 'ventasFecha':

		$fechaInicio=$_REQUEST["fechaInicio"];
		$fechaFin=$_REQUEST["fechaFin"];
		$idcliente=$_REQUEST["idcliente"];
		$res = $consultas->ConsultasVentas($fechaInicio, $fechaFin, $idcliente);

		$data = array();

		while ( $reg=$res->fetch_object() ) {
			$data[] = array(
				
				"0" => $reg->fecha,
				"1" => $reg->usuario,
				"2" => $reg->cliente,
				"3" => $reg->tipo_comprobante,
				"4" => $reg->serie_comprobante.' '.$reg->num_comprobante,
				"5" => $reg->total_venta,
				"6" => $reg->impuesto,
				"7" => ($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
										 '<span class="label bg-red">Anulado</span>'
			);
		}
		$resultados = array( 
			"sEcho"=>1, //INFORMACION PARA EL DATATABLES
			"iTotalRecords" => count($data), //ENVIAMOS EL TOTAL DE REGISTROS AL DATATABLE
			"iTotalDisplayRecords" => count($data), //TOTAL DE REGISTROS A VISUALIZAR
			"aaData" => $data
		);
		echo json_encode($resultados);
		break;

	case "totalCompraHoy":

		$res = $consultas->TotalCompraHoy();
		$reg = $res->fetch_object();
		$totalCompraHoy = $reg->total_compra;
		echo "<strong>".$totalCompraHoy." BS </strong>";
		
		break;

	case "totalVentaHoy":

		$res = $consultas->TotalVentaHoy();
		$reg = $res->fetch_object();
		$totalVentaHoy = $reg->total_venta;
		echo "<strong>".$totalVentaHoy." BS </strong>";		
		break;
}
