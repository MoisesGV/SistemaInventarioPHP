<?php
require_once "../modelos/Permiso.php";

$permiso = new Permiso();

switch ($_GET["op"]) {

	case 'listar':
		
		$res = $permiso->ListarPermiso();

		$data = array();

		while ( $reg=$res->fetch_object() ) {
			$data[] = array(
				"0" => $reg->nombre,
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