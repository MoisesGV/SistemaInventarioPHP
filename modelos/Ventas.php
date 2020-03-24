<?php

//INCLUIMOS INICIALMENTE LA CONEXION CON LA BASE DE DATOS

require "../config/Conexion.php";

/**
 * 
 */
class Ventas
{

	//INSERTAR VENTAS CON SUS DETALLE

	function InsertarVenta($idcliente, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_venta, $idarticulo, $cantidad, $precio_venta, $descuento)
	{
		$sql = "INSERT INTO venta (idcliente, idusuario, tipo_comprobante,serie_comprobante, 									num_comprobante, fecha_hora, impuesto, total_venta, estado) 
		VALUES  ('$idcliente', '$idusuario', '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$fecha_hora', '$impuesto', '$total_venta','Aceptado')";


		//EJECUTAMOS EL INSERT Y ALMACENAMOS EL ID DEL NUEVO INGRESO EN LA VARIABLE $idIngresoNew
		$idVentaNew=EjecutarConsulta_RetornarID($sql);
		$numElementos=0;
		$success=true;

		//EJECUTAMOS LAS SIGUIENTES INSTRUCCIONES SEGUN EL NÚMERO ID ARTICULOS TENGA EL INGRESO

		while ( $numElementos < count($idarticulo) ) 
		{
			//GUARDAMOS EN DETALLE_INGRESO

			$sqlDetalle = "INSERT INTO detalle_venta(idventa, idarticulo, cantidad, precio_venta, 												descuento) 
							VALUES ('$idVentaNew','$idarticulo[$numElementos]', '$cantidad[$numElementos]', '$precio_venta[$numElementos]','$descuento[$numElementos]')";
			EjecutarConsulta($sqlDetalle) or $success=false;
			$numElementos= $numElementos + 1;
		}
		return $success;
		
	}

	//ANULAR INGRESO

	function AnularVenta($idventa)
	{
		$sql = "UPDATE venta SET estado='Anulado' WHERE idventa = '$idventa'";
		return EjecutarConsulta($sql);
	}

	//MOSTRAR TODOS LOS DATOS DE UNA VENTA

	function MostrarVenta($idventa)
	{
		$sql = "SELECT V.idventa, DATE(V.fecha_hora) as fecha, V.idcliente, P.nombre as cliente, 				   V.idusuario, U.nombre as usuario, V.tipo_comprobante, V.serie_comprobante, 					   V.num_comprobante, V.total_venta, V.impuesto, V.estado 
				FROM venta V INNER JOIN persona P ON V.idcliente=P.idpersona 
							   INNER JOIN usuario U ON V.idusuario=U.idusuario 
				WHERE V.idventa='$idventa'";
		return EjecutarConsultaSimple($sql); //EjecutarConsultaSimple fue definida en Conexion.php

	}

	//MOSTRAR TODOS LOS DETALLES ESPECIFICOS DE UNA VENTA

	function ListarDetallesVentas($idventa)
	{
		$sql = "SELECT D.idventa, D.idarticulo, A.nombre, D.cantidad, D.precio_venta, D.descuento,						(D.cantidad*D.precio_venta-D.descuento) as subtotal
				FROM detalle_venta D INNER JOIN articulo A ON D.idarticulo=A.idarticulo
				WHERE D.idventa='$idventa'";
		return EjecutarConsulta($sql);
	}

	//LISTAR TODAS LAS VENTAS

	function ListarVentas()
	{
		$sql = "SELECT V.idventa, DATE(V.fecha_hora) as fecha, V.idcliente, P.nombre as cliente, 				   V.idusuario, U.nombre as usuario, V.tipo_comprobante, V.serie_comprobante, 					   V.num_comprobante, V.total_venta, V.impuesto, V.estado 
				FROM venta V INNER JOIN persona P ON V.idcliente=P.idpersona 
							   INNER JOIN usuario U ON V.idusuario=U.idusuario
				ORDER BY V.idventa desc";
		return EjecutarConsulta($sql);
	}

	function CabeceraFacturaVenta($idventa)		
	{
		$sql = "SELECT V.idventa, V.idcliente, P.nombre AS cliente, P.direccion, P.tipo_documento, P.num_documento, P.email, P.telefono, V.idusuario, U.nombre AS usuario, V.tipo_comprobante, V.serie_comprobante, V.num_comprobante, DATE(V.fecha_hora) AS fecha, V.impuesto, V.total_venta FROM venta V INNER JOIN persona P ON V.idcliente=P.idpersona INNER JOIN usuario U ON V.idusuario=U.idusuario  WHERE V.idventa='$idventa'";
		return EjecutarConsulta($sql);
	}

	function DetalleFacturaVenta($idventa)
	{
		$sql = "SELECT A.nombre as articulo, A.codigo, D.cantidad, D.precio_venta, D.descuento, (D.cantidad * D.precio_venta - D.descuento) AS subtotal FROM detalle_venta D INNER JOIN articulo A ON A.idarticulo=D.idarticulo  WHERE D.idventa='$idventa'";
		return EjecutarConsulta($sql);
	}
}