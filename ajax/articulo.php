<?php
require_once "../modelos/Articulo.php";

$articulo = new Articulo();

$idarticulo = isset($_POST["idarticulo"]) ? LimpiarCadena($_POST["idarticulo"]) : "";
$idcategoria = isset($_POST["idcategoria"]) ? LimpiarCadena($_POST["idcategoria"]) : "";
$codigo = isset($_POST["codigo"]) ? LimpiarCadena($_POST["codigo"]) : "";
$nombre = isset($_POST["nombre"]) ? LimpiarCadena($_POST["nombre"]) : "";
$stock = isset($_POST["stock"]) ? LimpiarCadena($_POST["stock"]) : "";
$descripcion = isset($_POST["descripcion"]) ? LimpiarCadena($_POST["descripcion"]) : "";
$imagen = isset($_POST["imagen"]) ? LimpiarCadena($_POST["imagen"]) : "";


switch ($_GET["op"]) {	

	case 'guardaryeditar':

		//SI EL ARCHIVO IMAGEN NO EXISTE O NO HA SIDO CARGADO, $imagen NO CONTENDRÁ NINGUN VALOR
		if( !file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']) ){
			$imagen=$_POST["imagenactual"];
		}

		else{

			//VALIDAMOS QUE EL ARCHIVO QUE SE SUBE SEA DE TIPO IMAGEN

			$extension = explode(".", $_FILES['imagen']['name']);
			if ( $_FILES['imagen']['type'] == "image/jpg" || 
				 $_FILES['imagen']['type'] == "image/jpeg" || 
				 $_FILES['imagen']['type'] == "image/png"   ) {
				
				//RENOMBRAMOS LA IMAGEN, CON UN FORMATO DE TIEMPO, PARA QUE NO SE REPITAN LOS NOMBRES
				$imagen = round(microtime(true)) . '.' . end($extension);

				//FINALMENTE MOVEMOS LAS IMAGEN A LA CARPETA DE DESTINO 	
				move_uploaded_file($_FILES['imagen']['tmp_name'], '../files/articulos/'.$imagen);

			}
		}

		if( empty($idarticulo) ){

			$res = $articulo->InsertarArticulo($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen);
			echo $res ? "Articulo registrado" :  " La Articulo no pudo ser registrado";

		}

		else{

			$res = $articulo->EditarArticulo($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen);
			echo $res ? "Articulo Actualizado" : " El Articulo no pudo ser actualizado";
		}
		break;

	case 'desactivar':
		
		$res = $articulo->DesactivarArticulo($idarticulo);
		echo $res ? "Artículo Agotado" : " El Artículo se encuentra Disponible";
		break;

	case 'activar':

		$res = $articulo->ActivarArticulo($idarticulo);
		echo $res ? "El Artículo se encuentra Disponible" : "Articulo Agotado";
		break;

	case 'mostrar':
		
		$res = $articulo->MostrarArticulo($idarticulo);
		echo json_encode($res);
		break;

	case 'listar':
		
		$res = $articulo->ListarArticulo();

		$data = array();

		while ( $reg=$res->fetch_object() ) {
			$data[] = array(

				//LLAMAMOS AL LA FUNCION MostrarArticuloFormulario DEFINIDA EN EL ARCHIVO ARTICULO.JS

				//SI CONDICION = 1 MOSTRAMOS EL BOTON PARA DESACTIVAR LA ARTICULO, CASO CONTRARIO MOSTRAMOS BOTON PARA ACTIVAR
				
				"0" => ($reg->condicion)? '<button class="btn btn-warning" onclick="MostrarArticuloFormulario('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.

					' <button class="btn btn-danger" onclick="DesactivarArticulo('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>':

					'<button class="btn btn-warning" onclick="MostrarArticuloFormulario('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.

					' <button class="btn btn-success" onclick="ActivarArticulo('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>',

				"1" => $reg->nombre,
				"2" => $reg->Categoria,
				"3" => $reg->codigo,
				"4" => $reg->stock,
				"5" =>"<img src='../files/articulos/" .$reg->imagen."' height='50px' width='50px' alt='$reg->nombre' >",
				"6" => ($reg->condicion)?'<span class="label bg-green">Disponible</span>':
										 '<span class="label bg-red">Agotado</span>'
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

	case 'selectCategoria':

		require_once "../modelos/Categoria.php";
		$categoria = new Categoria;

		$res=$categoria->SelectTodasCategoria();
		
		while( $reg = $res->fetch_object() ){

			echo '<option value='. $reg->idcategoria .'>'. $reg->nombre.'</option>';
		}
		
		break;
		

}