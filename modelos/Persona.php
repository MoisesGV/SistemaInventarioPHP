<?php

//INCLUIMOS INICIALMENTE LA CONEXION CON LA BASE DE DATOS

require "../config/Conexion.php";

/**
 * 
 */
class Persona
{
	
	function __construct()
	{
		
	}

	//INSERTAR PERSONA

	function InsertarPersona($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
	{
		$sql = "INSERT INTO persona (tipo_persona, nombre, tipo_documento, num_documento, direccion, 								telefono, email) 
				VALUES  ('$tipo_persona','$nombre','$tipo_documento', '$num_documento','$direccion', 			'$telefono', '$email')";
		return EjecutarConsulta($sql); //EjecutarConsulta fue definida en Conexion.php
	}

	//EDITAR PERSONA

	function EditarPersona($idpersona, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
	{
		$sql = "UPDATE persona SET tipo_persona = '$tipo_persona', nombre = '$nombre', 												tipo_documento = '$tipo_documento', num_documento = '$num_documento', 							direccion ='$direccion', telefono = '$telefono', email = '$email'
				WHERE idpersona = '$idpersona'";
									;
		return EjecutarConsulta($sql);
	}

	//ELIMINAR PERSONA

	function EliminarPersona($idpersona)
	{
		$sql = "DELETE FROM persona WHERE idpersona = '$idpersona'";
		return EjecutarConsulta($sql);
	}

	//MOSTRAR TODOS LOS DATOS DE UNA PERSONA

	function MostrarPersona($idpersona)
	{
		$sql = "SELECT * FROM persona where idpersona='$idpersona'";
		return EjecutarConsultaSimple($sql); //EjecutarConsultaSimple fue definida en Conexion.php

	}

	//LISTAR TODAS LOS PROVEEDORES

	function ListarProveedores()
	{
		$sql = "SELECT * FROM persona where tipo_persona='Proveedor'";
		return EjecutarConsulta($sql);
	}

	//LISTAR TODAS LOS CLIENTES

	function ListarClientes()
	{
		$sql = "SELECT * FROM persona where tipo_persona='Cliente'";
		return EjecutarConsulta($sql);

	}

}