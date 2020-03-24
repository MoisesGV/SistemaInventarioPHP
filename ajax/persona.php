<?php
require_once "../modelos/Persona.php";

$persona = new Persona();

$idpersona = isset($_POST["idpersona"]) ? LimpiarCadena($_POST["idpersona"]) : "";
$tipo_persona = isset($_POST["tipo_persona"]) ? LimpiarCadena($_POST["tipo_persona"]) : "";
$nombre = isset($_POST["nombre"]) ? LimpiarCadena($_POST["nombre"]) : "";
$tipo_documento = isset($_POST["tipo_documento"]) ? LimpiarCadena($_POST["tipo_documento"]) : "";
$num_documento = isset($_POST["num_documento"]) ? LimpiarCadena($_POST["num_documento"]) : "";
$direccion = isset($_POST["direccion"]) ? LimpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? LimpiarCadena($_POST["telefono"]) : "";
$email = isset($_POST["email"]) ? LimpiarCadena($_POST["email"]) : "";

switch ($_GET["op"]) {

	case 'guardaryeditar':

			if(empty($idpersona)){

				$res = $persona->InsertarPersona($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email);
				echo $res ? "Persona registrada" :  " La Persona no pudo ser registrada";

			}

			else{

				$res = $persona->EditarPersona($idpersona, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email);
				echo $res ? "Persona Actualizada" : " La Persona no pudo ser actualizada";
			}
		break;

	case 'eliminar':
		
		$res = $persona->EliminarPersona($idpersona);
		echo $res ? "Persona Eliminada" : " La Persona no pudo ser eliminada";
		break;

	case 'mostrar':
		
		$res = $persona->MostrarPersona($idpersona);
		echo json_encode($res);
		break;

	case 'listarProveedores':
		
		$res = $persona->ListarProveedores();

		$data = array();

		while ( $reg=$res->fetch_object() ) {
			$data[] = array(

				//LLAMAMOS AL LA FUNCION MostrarCategoriaFormulario DEFINIDA EN EL ARCHIVO CATEGORIA.JS
				
				"0" =>
					'<button class="btn btn-warning" onclick="MostrarProveedorFormulario('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.

					' <button class="btn btn-danger" onclick="EliminarProveedor('.$reg->idpersona.')">
					<i class="fa fa-trash"></i></button>',
				"1" => $reg->nombre,
				"2" => $reg->tipo_documento,
				"3" => $reg->num_documento,
				"4" => $reg->telefono,
				"5" => $reg->email
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
		
		$res = $persona->ListarClientes();

		$data = array();

		while ( $reg=$res->fetch_object() ) {
			$data[] = array(

				//LLAMAMOS AL LA FUNCION MostrarPersonaFormulario DEFINIDA EN EL ARCHIVO PERSONA.JS
				
				"0" =>'<button class="btn btn-warning" onclick="MostrarClienteFormulario('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.

					' <button class="btn btn-danger" onclick="EliminarCliente('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',

				"1" => $reg->nombre,
				"2" => $reg->tipo_documento,
				"3" => $reg->num_documento,
				"4" => $reg->telefono,
				"5" => $reg->email
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