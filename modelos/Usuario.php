<?php

//INCLUIMOS INICIALMENTE LA CONEXION CON LA BASE DE DATOS

require "../config/Conexion.php";

/**
 * 
 */
class Usuario
{
	
	function __construct()
	{
		
	}

	//INSERTAR USUARIO

	function InsertarUsuario($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos)
	{
		$sql = "INSERT INTO usuario (nombre, tipo_documento, num_documento, direccion, telefono, email, cargo, login, clave, imagen, condicion) 
		VALUES  ('$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$email', '$cargo', '$login', '$clave', '$imagen', '1')";

		//EJECUTAMOS EL INSERT Y ALMACENAMOS EL ID DEL NUEVO USUARIO EN LA VARIABLE $idUsuarioNew
		$idUsuarioNew=EjecutarConsulta_RetornarID($sql);
		$numElementos=0;
		$success=true;

		//EJECUTAMOS LAS SIGUIENTES INSTRUCCIONES SEGUN EL NÚMERO DE PERMISOS ASIGNADOS AL USUARIO
		while ( $numElementos < count($permisos) ) 
		{
			//GUARDAMOS LA RELACION USUARIO-PERMISO
			$sqlDetalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) 
							VALUES ('$idUsuarioNew','$permisos[$numElementos]')";
			EjecutarConsulta($sqlDetalle) or $success=false;
			$numElementos= $numElementos + 1;
		}
		return $success;
	}

	//EDITAR USUARIO

	function EditarUsuario($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $imagen, $permisos)
	{
		$sql = "UPDATE usuario SET nombre='$nombre', 
									tipo_documento = '$tipo_documento', 
									num_documento='$num_documento', 
									direccion='$direccion', 
									telefono='$telefono', 
									email='$email',  
									cargo='$cargo', 
									login='$login',
									imagen = '$imagen'
				WHERE idusuario = '$idusuario'";
									;
		EjecutarConsulta($sql);

		//ELIMINAR TODOS LOS PERMISOS ASIGNADOS PARA VOLVER A REGISTRARLOS

		$sqlDelete="DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
		EjecutarConsulta($sqlDelete);

		$numElementos=0;
		$success=true;

		//EJECUTAMOS LAS SIGUIENTES INSTRUCCIONES SEGUN EL NÚMERO DE PERMISOS ASIGNADOS AL USUARIO
		while ( $numElementos < count($permisos) ) 
		{
			//GUARDAMOS LA RELACION USUARIO-PERMISO
			$sqlDetalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) 
							VALUES ('$idusuario','$permisos[$numElementos]')";
			EjecutarConsulta($sqlDetalle) or $success=false;
			$numElementos= $numElementos + 1;
		}
		return $success;
	}

	function EditarUsuarioClave($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos)
	{
		$sql = "UPDATE usuario SET nombre='$nombre', 
									tipo_documento = '$tipo_documento', 
									num_documento='$num_documento', 
									direccion='$direccion', 
									telefono='$telefono', 
									email='$email',  
									cargo='$cargo', 
									login='$login', 
									clave='$clave', 
									imagen = '$imagen'
				WHERE idusuario = '$idusuario'";
									;
		EjecutarConsulta($sql);

		//ELIMINAR TODOS LOS PERMISOS ASIGNADOS PARA VOLVER A REGISTRARLOS

		$sqlDelete="DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
		EjecutarConsulta($sqlDelete);

		$numElementos=0;
		$success=true;

		//EJECUTAMOS LAS SIGUIENTES INSTRUCCIONES SEGUN EL NÚMERO DE PERMISOS ASIGNADOS AL USUARIO
		while ( $numElementos < count($permisos) ) 
		{
			//GUARDAMOS LA RELACION USUARIO-PERMISO
			$sqlDetalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) 
							VALUES ('$idusuario','$permisos[$numElementos]')";
			EjecutarConsulta($sqlDetalle) or $success=false;
			$numElementos= $numElementos + 1;
		}
		return $success;
	}

	//DESACTIVAR USUARIO

	function DesactivarUsuario($idusuario)
	{
		$sql = "UPDATE usuario SET condicion='0' WHERE idusuario = '$idusuario'";
		return EjecutarConsulta($sql);
		//return $sql;
	}

	//ACTIVAR USUARIO

	function ActivarUsuario($idusuario)
	{
		$sql = "UPDATE usuario SET condicion='1 ' WHERE idusuario = '$idusuario'";
		return EjecutarConsulta($sql);
		//return $sql;
	}

	//MOSTRAR TODOS LOS DATOS DE UN USUARIO

	function MostrarUsuario($idusuario)
	{
		$sql = "SELECT * FROM usuario where idusuario='$idusuario'";
		return EjecutarConsultaSimple($sql); //EjecutarConsultaSimple fue definida en Conexion.php

	}

	//LISTAR TODAS LOS USUARIOS

	function ListarUsuario()
	{
		$sql = "SELECT * from usuario";
		return EjecutarConsulta($sql);
	}

	//LISTAR PERMISOS DE UN USUARIO

	function ListarPermisosUsuario($idusuario)
	{
		$sql="SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
		return EjecutarConsulta($sql);
	}

	//VERIFICAR ACCESO AL SISTEMA
	function VerificarUsuario($login, $clave)
	{
		$sql="SELECT idusuario, nombre, tipo_documento, num_documento, telefono, email, cargo,imagen, login FROM usuario WHERE login='$login' AND clave='$clave' AND condicion='1'";
		return EjecutarConsulta($sql);	
	}

}