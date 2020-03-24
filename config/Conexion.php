<?php

require_once "global.php";
$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');

//SI TENEMOS UN POSIBLE ERROR EN LA CONEXION LO MUESTRA EN PANTALLA

if (mysqli_connect_errno())
{
	printf("FALLO LA CONEXION A LA BASE DE DATOS: %s\n",mysqli_connect_error());
	exit();
}

if (!function_exists('EjecutarConsulta'))
{
	function EjecutarConsulta($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);
		return $query;
	}

	function EjecutarConsultaSimple($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);
		$row = $query->fetch_assoc();
		return $row;
	}

	function EjecutarConsulta_RetornarID($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);
		return $conexion->insert_id;
	}

	function LimpiarCadena($str)
	{
		global $conexion;
		$str = mysqli_real_escape_string($conexion, trim($str));
		return htmlspecialchars($str);
	}
}