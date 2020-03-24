<?php

//INCLUIMOS INICIALMENTE LA CONEXION CON LA BASE DE DATOS

require "../config/Conexion.php";

/**
 * 
 */
class Articulo
{
	
	function __construct()
	{
		
	}

	//INSERTAR ARTICULO

	function InsertarArticulo($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen)
	{
		$sql = "INSERT INTO articulo (idcategoria, codigo, nombre, stock, descripcion, imagen, condicion) 
		VALUES  ('$idcategoria', '$codigo', '$nombre', '$stock', '$descripcion', '$imagen', '1')";
		return EjecutarConsulta($sql); //EjecutarConsulta fue definida en Conexion.php
	}

	//EDITAR ARTICULO

	function EditarArticulo($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen)
	{
		$sql = "UPDATE articulo SET codigo='$codigo', nombre = '$nombre', 
									stock ='$stock', descripcion = '$descripcion', imagen = '$imagen'
				WHERE idarticulo = '$idarticulo'";
									;
		return EjecutarConsulta($sql);
	}

	//DESACTIVAR ARTICULO

	function DesactivarArticulo($idarticulo)
	{
		$sql = "UPDATE articulo SET condicion = '0'
				WHERE idarticulo = '$idarticulo'";
		return EjecutarConsulta($sql);
	}

	//ACTIVAR ARTICULO

	function ActivarArticulo($idarticulo)
	{
		$sql = "UPDATE articulo SET condicion = '1'
				WHERE idarticulo = '$idarticulo'";
		return EjecutarConsulta($sql);
	}

	//MOSTRAR TODOS LOS DATOS DE UN ARTICULO

	function MostrarArticulo($idarticulo)
	{
		$sql = "SELECT * FROM articulo where idarticulo='$idarticulo'";
		return EjecutarConsultaSimple($sql); //EjecutarConsultaSimple fue definida en Conexion.php

	}

	//LISTAR TODAS LOS ARTICULOS

	function ListarArticulo()
	{
		$sql = "SELECT A.idarticulo, C.idcategoria, C.nombre AS Categoria, A.codigo, A.nombre, A.stock, A.				descripcion, A.imagen, A.condicion 
				FROM articulo A INNER JOIN categoria C ON A.idcategoria = C.idcategoria";
		return EjecutarConsulta($sql);
	}

	//LISTAR TODAS LOS ARTICULOS ACTIVOS

	function ListarArticulosActivos()
	{
		$sql = "SELECT A.idarticulo, C.idcategoria, C.nombre AS Categoria, A.codigo, A.nombre, A.stock, A.				descripcion, A.imagen, A.condicion 
				FROM articulo A INNER JOIN categoria C ON A.idcategoria = C.idcategoria
				WHERE A.condicion='1'";
		return EjecutarConsulta($sql);
	}

	//LISTAR LOS ARTICULOS ACTIVOS PARA LA VENTA, JUNTO CON EL ULTIMO PRECIO DE VENTA Y LA CANTIDAD DE ARTICULOS DISPONIBLES
	//EL PRECIO DE VENTA PARA EL ARTICULO, SERA EL ULTIMO PRECIO DE VENTA ASIGNADO EN ULTIMO INGRESO

	function ListarArticulosActivosVentas()
	{
		$sql="SELECT A.idarticulo, A.idcategoria, C.nombre AS categoria, A.codigo, A.nombre, A.stock, 		(SELECT precio_venta from detalle_ingreso WHERE idarticulo = A.idarticulo ORDER BY iddetalle_ingreso desc limit 0,1) AS precio_venta, A.descripcion, A.imagen, A.condicion 		from articulo A INNER JOIN categoria C ON A.idcategoria=C.idcategoria WHERE A.condicion='1'";
		return EjecutarConsulta($sql);
	}
}