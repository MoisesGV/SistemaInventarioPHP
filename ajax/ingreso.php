<?php

//SI LA SESION NO HA SIDO INICIADA INICIAMOS SESION
  if (strlen(session_id()) < 1) 
    session_start();

require_once "../modelos/Ingreso.php";

$ingreso = new Ingreso();

$idingreso = isset($_POST["idingreso"]) ? LimpiarCadena($_POST["idingreso"]) : "";
$idproveedor = isset($_POST["idproveedor"]) ? LimpiarCadena($_POST["idproveedor"]) : "";
$idusuario = $_SESSION['idusuario'];
$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? LimpiarCadena($_POST["tipo_comprobante"]) : "";
$serie_comprobante = isset($_POST["serie_comprobante"]) ? LimpiarCadena($_POST["serie_comprobante"]) : "";
$num_comprobante = isset($_POST["num_comprobante"]) ? LimpiarCadena($_POST["num_comprobante"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? LimpiarCadena($_POST["fecha_hora"]) : "";
$impuesto = isset($_POST["impuesto"]) ? LimpiarCadena($_POST["impuesto"]) : "";
$total_compra = isset($_POST["total_compra"]) ? LimpiarCadena($_POST["total_compra"]) : "";

switch ($_GET["op"]) {

	case 'guardar':
 
			if(empty($idingreso)){

				$res = $ingreso->InsertarIngreso($idproveedor, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_compra, $_POST["idarticulo"], $_POST["cantidad"], $_POST["precio_compra"], $_POST["precio_venta"]);
				echo $res ? "Compra registrada" :  " La Compra no pudo ser registrada";
			}

			else{
			}
		break;

	case 'anular':
		
		$res = $ingreso->AnularIngreso($idingreso);
		echo $res ? "Compra Anulada" : " La Compra no pudo ser Anulada";
		break;

	case 'mostrar':
		
		$res = $ingreso->MostrarIngreso($idingreso);
		echo json_encode($res);
		break;

	case 'mostrarDetallesIngreso':
		//RECIBIMOS EL INGRESO
		$id=$_GET['id'];
		$total=0;
		$res = $ingreso->ListarDetallesIngreso($id);

		echo '<thead style="background-color: #1e282c; color:#d3d3d3">
				<th>Opciones</th>
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Subtotal</th>
            </thead>';

		while ( $reg = $res->fetch_object() ) {
			echo '<tr class="filas">
					<td></td>
					<td>'.$reg->nombre.'</td>
					<td>'.$reg->cantidad.'</td>
					<td>'.$reg->precio_compra.'</td>
					<td>'.$reg->precio_venta.'</td>
					<td>'.$reg->precio_compra * $reg->cantidad.'</td>
				</tr>';
			$total=$total+($reg->precio_compra * $reg->cantidad);
		}

		echo '<tfoot>
                <th>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><h4 id="total">BS '.$total.'</h4><input type="hidden" name="total_compra" id="total_compra"></th>
            </tfoot>';
		
		break;

	case 'listar':
		
		$res = $ingreso->ListarIngreso();

		$data = array();

		while ( $reg=$res->fetch_object() ) {
			$data[] = array(
				
				"0" => ($reg->estado=='Aceptado')? '<button class="btn btn-success" onclick="MostrarIngresoFormulario('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>'.

					' <button class="btn btn-danger" onclick="AnularIngreso('.$reg->idingreso.')"><i class="fa fa-close"></i></button>':

					'<button class="btn btn-success" onclick="MostrarIngresoFormulario('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>',
				"1" => $reg->fecha,
				"2" => $reg->proveedor,
				"3" => $reg->usuario,
				"4" => $reg->tipo_comprobante,
				"5" => $reg->serie_comprobante.'-'.$reg->num_comprobante,
				"6" => $reg->total_compra,
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

		case 'listarProveedores':

			require_once "../modelos/Persona.php";
			$proveedor = new Persona();

			$res = $proveedor->ListarProveedores();

			while($reg = $res->fetch_object())
			{
				echo '<option value="'.$reg->idpersona.'">'.$reg->nombre.'</option>';
			}	
		break;

		case 'listarArticulos':
			require_once "../modelos/Articulo.php";
			$articulo = new Articulo();

			$res = $articulo->ListarArticulosActivos();

			$data = array();

			while ( $reg=$res->fetch_object() ) {
			$data[] = array(
				//EN LA FUNCION AGREGARDETALLE PASAMOS EL ID Y EL NOMBRE DEL ARTICULO SELECCIONADO
				"0" => '<button class="btn btn-success" onclick="AgregarDetalle('.$reg->idarticulo.',\'' .$reg->nombre.'\')"><span class="fa fa-plus">Agregar</span></button>',
				"1" => $reg->nombre,
				"2" => $reg->Categoria,
				"3" => $reg->codigo,
				"4" => $reg->stock,
				"5" =>"<img src='../files/articulos/" .$reg->imagen."' height='50px' width='50px' alt='$reg->nombre' >"
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