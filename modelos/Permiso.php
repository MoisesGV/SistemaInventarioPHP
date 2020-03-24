<?php

//INCLUIMOS INICIALMENTE LA CONEXION CON LA BASE DE DATOS

require "../config/Conexion.php";

/**
 * 
 */
class Permiso
{
	
	function __construct()
	{
		
	}
	//LISTAR TODOS LOS PERMISOS

	function ListarPermiso()
	{
		$sql = "SELECT * FROM permiso";
		return EjecutarConsulta($sql);
	}
}