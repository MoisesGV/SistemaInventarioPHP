<?php

//INCLUIMOS INICIALMENTE LA CONEXION CON LA BASE DE DATOS

require "../config/Conexion.php";

/**
 * 
 */
class Consultas
{
	
	function __construct()
	{
		
	}

	//LISTAR TODAS LAS COMPRAS USANDO COMO FILTRO LAS FECHAS

	function ConsultasCompras($fechaInicio, $fechaFin)
	{
		$sql = "SELECT DATE(I.fecha_hora) AS fecha, U.nombre AS usuario, P.nombre AS proveedor, 						I.tipo_comprobante, I.serie_comprobante, I.num_comprobante, I.total_compra, 					I.impuesto, I.estado 
		FROM ingreso I INNER JOIN persona P ON I.idproveedor=P.idpersona 
						INNER JOIN usuario U ON U.idusuario=I.idusuario 
		WHERE DATE(I.fecha_hora)>='$fechaInicio' AND DATE(I.fecha_hora)<='$fechaFin'";
		return EjecutarConsulta($sql);
	}

	//LISTAR TODAS LAS VENTAS USANDO COMO FILTRO LAS FECHAS

	function ConsultasVentas($fechaInicio, $fechaFin, $idcliente)
	{
		$sql = "SELECT DATE(V.fecha_hora) AS fecha, U.nombre AS usuario, P.nombre AS cliente, 						V.tipo_comprobante, V.serie_comprobante, V.num_comprobante, V.total_venta, 					V.impuesto, V.estado 
		FROM venta V INNER JOIN persona P ON V.idcliente=P.idpersona 
						INNER JOIN usuario U ON U.idusuario=V.idusuario 
		WHERE DATE(V.fecha_hora)>='$fechaInicio' AND DATE(V.fecha_hora)<='$fechaFin' AND V.idcliente='$idcliente'";
		return EjecutarConsulta($sql);
	}

	function TotalCompraHoy()
	{
		$sql="SELECT IFNULL(SUM(total_compra),0) AS total_compra FROM ingreso WHERE DATE(fecha_hora)=curdate()";
		return EjecutarConsulta($sql);
	}

	function TotalVentaHoy()
	{
		$sql="SELECT IFNULL(SUM(total_venta),0) AS total_venta FROM venta WHERE DATE(fecha_hora)=curdate()";
		return EjecutarConsulta($sql);
	}

	function ComprasUltimos10Dias()
	{
		//CONCAT(DAY(fecha_hora),'-',MONTH(fecha_hora))
		$sql="SELECT DATE_FORMAT(fecha_hora, '%d %b') AS fecha, SUM(total_compra) AS total FROM ingreso GROUP BY fecha_hora ORDER BY fecha_hora DESC LIMIT 0,10";
		return EjecutarConsulta($sql);
	}

	function VentasUltimos10Dias()
	{
		$sql="SELECT DATE_FORMAT(fecha_hora, '%d %b') AS fecha, SUM(total_venta) AS total FROM venta GROUP BY fecha_hora ORDER BY fecha_hora DESC LIMIT 0,10";
		return EjecutarConsulta($sql);
	}

}