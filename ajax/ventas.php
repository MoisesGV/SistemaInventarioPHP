<?php

//SI LA SESION NO HA SIDO INICIADA INICIAMOS SESION
  if (strlen(session_id()) < 1) 
    session_start();

require_once "../modelos/Ventas.php";

$venta = new Ventas();

$idventa = isset($_POST["idventa"]) ? LimpiarCadena($_POST["idventa"]) : "";
$idcliente = isset($_POST["idcliente"]) ? LimpiarCadena($_POST["idcliente"]) : "";
$idusuario = $_SESSION['idusuario'];
$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? LimpiarCadena($_POST["tipo_comprobante"]) : "";
$serie_comprobante = isset($_POST["serie_comprobante"]) ? LimpiarCadena($_POST["serie_comprobante"]) : "";
$num_comprobante = isset($_POST["num_comprobante"]) ? LimpiarCadena($_POST["num_comprobante"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? LimpiarCadena($_POST["fecha_hora"]) : "";
$impuesto = isset($_POST["impuesto"]) ? LimpiarCadena($_POST["impuesto"]) : "";
$total_venta = isset($_POST["total_venta"]) ? LimpiarCadena($_POST["total_venta"]) : "";

switch ($_GET["op"]) {

	case 'guardar':
 
			if(empty($idventa)){

				$res = $venta->InsertarVenta($idcliente, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_venta, $_POST["idarticulo"], $_POST["cantidad"], $_POST["precio_venta"], $_POST["descuento"]);
				echo $res ? "Venta registrada" :  " La Venta no pudo ser registrada";
			}

			else{
				echo "La Venta no pudo ser registrada";
			}
		break;

	case 'anular':
		
		$res = $venta->AnularVenta($idventa);
		echo $res ? "Venta Anulada" : " La Venta no pudo ser Anulada";
		break;
 
	case 'mostrar':
		
		$res = $venta->MostrarVenta($idventa);
		echo json_encode($res);
		break;

	case 'mostrarDetallesVentas':
		//RECIBIMOS EL INGRESO
		$id=$_GET['id'];
		$total=0;
		$res = $venta->ListarDetallesVentas($id);

		echo '<thead style="background-color: #1e282c; color:#d3d3d3">
				<th>Opciones</th>
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Precio Venta</th>
                <th>Descuento</th>
                <th>Subtotal</th>
            </thead>';

		while ( $reg = $res->fetch_object() ) {
			echo '<tr class="filas">
					<td></td>
					<td>'.$reg->nombre.'</td>
					<td>'.$reg->cantidad.'</td>
					<td>'.$reg->precio_venta.'</td>
					<td>'.$reg->descuento.'</td>
					<td>'.$reg->subtotal.'</td>
				</tr>';
			$total=$total+$reg->subtotal;
		}

		echo '<tfoot>
                <th>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><h4  id="total">BS '.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th>
            </tfoot>';
		
		break;

	case 'listar':
		
		$res = $venta->ListarVentas();

		$data = array();

		while ( $reg=$res->fetch_object() ) {
			if($reg->tipo_comprobante=='Factura'){
				$url='../reportes/facturaVenta.php?id=';
			}else{
				$url='../reportes/ticket.php?id=';
			}
			$data[] = array(
				
				"0" => ($reg->estado=='Aceptado')? '<button class="btn btn-success" onclick="MostrarVentasFormulario('.$reg->idventa.')"><i class="fa fa-eye"></i></button>'.

					' <button class="btn btn-danger" onclick="AnularVentas('.$reg->idventa.')"><i class="fa fa-close"></i></button>'.

					' <a target="_blank" href="'.$url.$reg->idventa.'" title=""><button class="btn btn-warning"><i class="fa fa-file"></i></button></a>':

					'<button class="btn btn-success" onclick="MostrarVentasFormulario('.$reg->idventa.')"><i class="fa fa-eye"></i></button>',
				"1" => $reg->fecha,
				"2" => $reg->cliente,
				"3" => $reg->usuario,
				"4" => $reg->tipo_comprobante,
				"5" => $reg->serie_comprobante.'-'.$reg->num_comprobante,
				"6" => $reg->total_venta,
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

		case 'listarClientes':

			require_once "../modelos/Persona.php";
			$cliente = new Persona();

			$res = $cliente->ListarClientes(); 

			while($reg = $res->fetch_object())
			{
				echo '<option value="'.$reg->idpersona.'">'.$reg->nombre.'</option>';
			}

		break;

		case 'listarArticulosVenta':
			require_once "../modelos/Articulo.php";
			$articulo = new Articulo();

			$res = $articulo->ListarArticulosActivosVentas();

			$data = array();

			while ( $reg=$res->fetch_object() ) {
			$data[] = array(
				//EN LA FUNCION AGREGARDETALLE PASAMOS EL ID Y EL NOMBRE DEL ARTICULO SELECCIONADO
				"0" => '<button class="btn btn-success" onclick="AgregarDetalle('.$reg->idarticulo.',\'' .$reg->nombre.'\',\''.$reg->precio_venta.'\',\''.$reg->stock.'\')"><span class="fa fa-plus">Agregar</span></button>',
				"1" => $reg->nombre,
				"2" => $reg->categoria,
				"3" => $reg->codigo,
				"4" => $reg->stock,
				"5" => $reg->precio_venta,
				"6" =>"<img src='../files/articulos/" .$reg->imagen."' height='50px' width='50px' alt='$reg->nombre' >"
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
}