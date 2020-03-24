<?php
require_once "../modelos/Categoria.php";

$categoria = new Categoria();

$idcategoria = isset($_POST["idcategoria"]) ? LimpiarCadena($_POST["idcategoria"]) : "";
$nombre = isset($_POST["nombre"]) ? LimpiarCadena($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? LimpiarCadena($_POST["descripcion"]) : "";

switch ($_GET["op"]) {

	case 'guardaryeditar':

			if(empty($idcategoria)){

				$res = $categoria->InsertarCategoria($nombre,$descripcion);
				echo $res ? "Categoría registrada" :  " La Categoría no pudo ser registrada";

			}

			else{

				$res = $categoria->EditarCategoria($idcategoria, $nombre, $descripcion);
				echo $res ? "Categoria Actualizada" : " La Categoria no pudo ser actualizada";
			}
		break;

	case 'desactivar':
		
		$res = $categoria->DesactivarCategoria($idcategoria);
		echo $res ? "Categoria Desactivada" : " La Categoria no pudo ser Desactivada";
		break;

	case 'activar':

		$res = $categoria->ActivarCategoria($idcategoria);
		echo $res ? "Categoria Activada" : " La Categoria no pudo ser Activada";
		break;

	case 'mostrar':
		
		$res = $categoria->MostrarCategoria($idcategoria);
		echo json_encode($res);
		break;

	case 'listar':
		
		$res = $categoria->ListarCategoria();

		$data = array();

		while ( $reg=$res->fetch_object() ) {
			$data[] = array(

				//LLAMAMOS AL LA FUNCION MostrarCategoriaFormulario DEFINIDA EN EL ARCHIVO CATEGORIA.JS

				//SI CONDICION = 1 MOSTRAMOS EL BOTON PARA DESACTIVAR LA CATEGORIA, CASO CONTRARIO MOSTRAMOS BOTON PARA ACTIVAR
				
				"0" => ($reg->condicion)? '<button class="btn btn-warning" onclick="MostrarCategoriaFormulario('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.

					' <button class="btn btn-danger" onclick="DesactivarCategoria('.$reg->idcategoria.')"><i class="fa fa-close"></i></button>':

					'<button class="btn btn-warning" onclick="MostrarCategoriaFormulario('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.

					' <button class="btn btn-success" onclick="ActivarCategoria('.$reg->idcategoria.')"><i class="fa fa-check"></i></button>',

				"1" => $reg->nombre,
				"2" => $reg->descripcion,
				"3" => ($reg->condicion)?'<span class="label bg-green">Activa</span>':
										 '<span class="label bg-red">Inactiva</span>'
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