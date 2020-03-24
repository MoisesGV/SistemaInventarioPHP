<?php

//INCLUIMOS INICIALMENTE LA CONEXION CON LA BASE DE DATOS

require "../config/Conexion.php";

/**
 * 
 */
class Categoria
{
	
	function __construct()
	{
		
	}

	//INSERTAR CATEGORIA

	function InsertarCategoria($nombre, $descripcion)
	{
		$sql = "INSERT INTO categoria (nombre, descripcion, condicion) 
		VALUES  ('$nombre','$descripcion','1')";
		return EjecutarConsulta($sql); //EjecutarConsulta fue definida en Conexion.php
	}

	//EDITAR CATEGORIA

	function EditarCategoria($idcategoria, $nombre, $descripcion)
	{
		$sql = "UPDATE categoria SET nombre = '$nombre', descripcion = '$descripcion'
				WHERE idcategoria = '$idcategoria'";
									;
		return EjecutarConsulta($sql);
	}

	//DESACTIVAR CATEGORIA

	function DesactivarCategoria($idcategoria)
	{
		$sql = "UPDATE categoria SET condicion = '0'
				WHERE idcategoria = '$idcategoria'";
		return EjecutarConsulta($sql);
	}

	//ACTIVAR CATEGORIA

	function ActivarCategoria($idcategoria)
	{
		$sql = "UPDATE categoria SET condicion = '1'
				WHERE idcategoria = '$idcategoria'";
		return EjecutarConsulta($sql);
	}

	//MOSTRAR TODOS LOS DATOS DE UNA CATEGORIA

	function MostrarCategoria($idcategoria)
	{
		$sql = "SELECT * FROM categoria where idcategoria='$idcategoria'";
		return EjecutarConsultaSimple($sql); //EjecutarConsultaSimple fue definida en Conexion.php

	}

	//LISTAR TODAS LAS CATEGORIAS

	function ListarCategoria()
	{
		$sql = "SELECT * FROM categoria";
		return EjecutarConsulta($sql);
	}

	//LISTAR TODAS LAS CATEGORIAS Y MOSTRARLAS EN EL SELECT

	function SelectTodasCategoria()
	{
		$sql = "SELECT * FROM categoria where condicion=1";
		return EjecutarConsulta($sql);
	}
}