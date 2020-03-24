<?php

//INCLUIMOS INICIALMENTE LA CONEXION CON LA BASE DE DATOS

require "../config/Conexion.php";

/**
 * 
 */
class Ingreso
{

	//INSERTAR COMPRA CON SU DETALLE

	function InsertarIngreso($idproveedor, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_compra, $idarticulo, $cantidad, $precio_compra, $precio_venta)
	{
		$sql = "INSERT INTO ingreso (idproveedor, idusuario, tipo_comprobante,serie_comprobante, 									num_comprobante, fecha_hora, impuesto, total_compra, estado) 
		VALUES  ('$idproveedor', '$idusuario', '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$fecha_hora', '$impuesto', '$total_compra','Aceptado')";


		//EJECUTAMOS EL INSERT Y ALMACENAMOS EL ID DEL NUEVO INGRESO EN LA VARIABLE $idIngresoNew
		$idIngresoNew=EjecutarConsulta_RetornarID($sql);
		$numElementos=0;
		$success=true;

		//EJECUTAMOS LAS SIGUIENTES INSTRUCCIONES SEGUN EL NÚMERO ID ARTICULOS TENGA EL INGRESO

		while ( $numElementos < count($idarticulo) ) 
		{
			//GUARDAMOS EN DETALLE_INGRESO

			$sqlDetalle = "INSERT INTO detalle_ingreso(idingreso, idarticulo, cantidad, precio_compra, 												precio_venta) 
							VALUES ('$idIngresoNew','$idarticulo[$numElementos]', '$cantidad[$numElementos]', '$precio_compra[$numElementos]','$precio_venta[$numElementos]')";
			EjecutarConsulta($sqlDetalle) or $success=false;
			$numElementos= $numElementos + 1;
		}
		return $success;
		
	}

	//ANULAR INGRESO

	function AnularIngreso($idingreso)
	{
		$sql = "UPDATE ingreso SET estado='Anulado' WHERE idingreso = '$idingreso'";
		return EjecutarConsulta($sql);
	}

	//MOSTRAR TODOS LOS DATOS DE UN INGRESO

	function MostrarIngreso($idingreso)
	{
		$sql = "SELECT i.idingreso, DATE(i.fecha_hora) as fecha, i.idproveedor, p.nombre as proveedor, 				   i.idusuario, u.nombre as usuario, i.tipo_comprobante, i.serie_comprobante, 					   i.num_comprobante, i.total_compra, i.impuesto, i.estado 
				FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona 
							   INNER JOIN usuario u ON i.idusuario=u.idusuario 
				WHERE i.idingreso='$idingreso'";
		return EjecutarConsultaSimple($sql); //EjecutarConsultaSimple fue definida en Conexion.php

	}

	//MOSTRAR TODOS LOS DETALLES ESPECIFICOS DE UN INGRESO

	function ListarDetallesIngreso($idingreso)
	{
		$sql = "SELECT D.idingreso, D.idarticulo, A.nombre, D.cantidad, D.precio_compra, D.precio_venta	 		FROM detalle_ingreso D INNER JOIN articulo A ON D.idarticulo=A.idarticulo
				WHERE D.idingreso='$idingreso'";
		return EjecutarConsulta($sql);
	}

	//LISTAR TODAS LOS INGRESOS

	function ListarIngreso()
	{
		$sql = "SELECT i.idingreso, DATE(i.fecha_hora) as fecha, i.idproveedor, p.nombre as proveedor, 				   i.idusuario, u.nombre as usuario, i.tipo_comprobante, i.serie_comprobante, 					   i.num_comprobante, i.total_compra, i.impuesto, i.estado 
				FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona 
							   INNER JOIN usuario u ON i.idusuario=u.idusuario
				ORDER BY i.idingreso desc";
		return EjecutarConsulta($sql);
	}
}