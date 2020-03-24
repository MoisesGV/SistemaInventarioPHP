<?php
session_start();
require_once "../modelos/Usuario.php";

$usuario = new Usuario();

$idusuario = isset($_POST["idusuario"]) ? LimpiarCadena($_POST["idusuario"]) : "";
$nombre = isset($_POST["nombre"]) ? LimpiarCadena($_POST["nombre"]) : "";
$tipo_documento = isset($_POST["tipo_documento"]) ? LimpiarCadena($_POST["tipo_documento"]) : "";
$num_documento = isset($_POST["num_documento"]) ? LimpiarCadena($_POST["num_documento"]) : "";
$direccion = isset($_POST["direccion"]) ? LimpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? LimpiarCadena($_POST["telefono"]) : "";
$email = isset($_POST["email"]) ? LimpiarCadena($_POST["email"]) : "";
$cargo = isset($_POST["cargo"]) ? LimpiarCadena($_POST["cargo"]) : "";
$login = isset($_POST["login"]) ? LimpiarCadena($_POST["login"]) : "";
$clave = isset($_POST["clave"]) ? LimpiarCadena($_POST["clave"]) : "";
$imagen = isset($_POST["imagen"]) ? LimpiarCadena($_POST["imagen"]) : "";

switch ($_GET["op"]) {	

	case 'guardaryeditar':

		//SI EL ARCHIVO IMAGEN NO EXISTE O NO HA SIDO CARGADO, $imagen NO CONTENDRÁ NINGUN VALOR
		if( !file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']) ){
			$imagen=$_POST["imagenactual"];
		}

		else{

			//VALIDAMOS QUE EL ARCHIVO QUE SE SUBE SEA DE TIPO IMAGEN

			$extension = explode(".", $_FILES['imagen']['name']);
			if ( $_FILES['imagen']['type'] == "image/jpg" || 
				 $_FILES['imagen']['type'] == "image/jpeg" || 
				 $_FILES['imagen']['type'] == "image/png"   ) {
				
				//RENOMBRAMOS LA IMAGEN, CON UN FORMATO DE TIEMPO, PARA QUE NO SE REPITAN LOS NOMBRES
				$imagen = round(microtime(true)) . '.' . end($extension);

				//FINALMENTE MOVEMOS LAS IMAGEN A LA CARPETA DE DESTINO 	
				move_uploaded_file($_FILES['imagen']['tmp_name'], '../files/usuarios/'.$imagen);

			}
		}

		if ($clave=='') {

			$claveHash=$clave;
		}else{		

			$claveHash=hash('SHA256',$clave);
		}

		if( empty($idusuario) ){

			$res = $usuario->InsertarUsuario($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $claveHash, $imagen, $_POST['permiso']);
			echo $res ? "Usuario registrado" :  " El Usuario no pudo ser registrado";

		}

		else{

			if ($claveHash=='') {
				$res = $usuario->EditarUsuario($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $imagen, $_POST['permiso']);
			}

			else{
				$res = $usuario->EditarUsuarioClave($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $claveHash, $imagen, $_POST['permiso']);
			}
			echo $res ? "Usuario Actualizado" : " El Usuario no pudo ser actualizado";
		}
		break;

	case 'desactivar':
		
		$res = $usuario->DesactivarUsuario($idusuario);
		echo $res ? "Usuario Desactivado" : " El Usuario no pudo ser Desactivado";
		break;

	case 'activar':

		$res = $usuario->ActivarUsuario($idusuario);
		echo $res ? "Usuario Activo" : "El Usuario no pudo ser Activado";
		break;

	case 'mostrar':
		
		$res = $usuario->MostrarUsuario($idusuario);
		echo json_encode($res);
		break;

	case 'listar':
		
		$res = $usuario->ListarUsuario();

		$data = array();

		while ( $reg=$res->fetch_object() ) {
			$data[] = array(

				//LLAMAMOS AL LA FUNCION MostrarArticuloFormulario DEFINIDA EN EL ARCHIVO ARTICULO.JS

				//SI CONDICION = 1 MOSTRAMOS EL BOTON PARA DESACTIVAR LA ARTICULO, CASO CONTRARIO MOSTRAMOS BOTON PARA ACTIVAR
				
				"0" => ($reg->condicion)?
				 '<button class="btn btn-warning" onclick="MostrarUsuarioFormulario('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.

					' <button class="btn btn-danger" onclick="DesactivarUsuario('.$reg->idusuario.')"><i class="fa fa-close"></i></button>'

					:'<button class="btn btn-warning" onclick="MostrarUsuarioFormulario('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.

					' <button class="btn btn-success" onclick="ActivarUsuario('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',

				"1" => $reg->nombre,
				"2" => $reg->tipo_documento,
				"3" => $reg->num_documento,
				"4" => $reg->telefono,
				"5" => $reg->email,
				"6" => $reg->login,
				"7" =>"<img src='../files/usuarios/" .$reg->imagen."' height='50px' width='50px' alt='$reg->nombre' >",
				"8" => ($reg->condicion)?'<span class="label bg-green">Activo</span>':
										 '<span class="label bg-red">Desactivado</span>'
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

		case 'permisos':
			//OBTENEMOS EL LISTADO DE LOS PERMISOS DESDE LA TABLA PERMISOS
			require_once "../modelos/Permiso.php";
			$permiso = new Permiso();
			$res=$permiso->ListarPermiso();

			//OBTENER LOS PERMISOS DE UN USUARIO
			$idusuario=$_GET['id'];
			$permisosUsuario=$usuario->ListarPermisosUsuario($idusuario);

			$permisos=array();

			//ALMACENAMOS LOS PERMISOS EN EL ARRAY PERMISOS

			while ($per =$permisosUsuario->fetch_object()) {
				array_push($permisos, $per->idpermiso);
			}

			//MOSTRAMOS LA LISTA DE PERMISOS EN LA VISTA Y SI FUERON O NO SELECCIONADOS
			while (	$reg = $res->fetch_object()) {

						$sw=in_array($reg->idpermiso, $permisos)?'checked':'';

						echo '<li> 
								<input type="checkbox" '.$sw.' name="permiso[]" value="'.$reg->idpermiso.'"> 	
								'.$reg->nombre.' 
							  </li>';	
					}		
		break;

		case 'verificar':

			$usuarioLogin=$_POST['login'];
			$usuarioClave=$_POST['clave'];

			$claveHash=hash("SHA256",$usuarioClave);

			$res=$usuario->VerificarUsuario($usuarioLogin,$claveHash);

			$accesoUsuario=$res->fetch_object();

			//SI EXISTE ALGUN USUARIO CON ESE USUARIO, ESA CONTRASEÑA Y SE ENCUENTRA ACTIVO
			if (isset($accesoUsuario)){

				//DECLARAMOS VARIABLES DE SESION
				$_SESSION['idusuario']=$accesoUsuario->idusuario;
				$_SESSION['nombre']=$accesoUsuario->nombre;
				$_SESSION['imagen']=$accesoUsuario->imagen;
				$_SESSION['login']=$accesoUsuario->login;

				//OBTENEMOS LOS PERMISOS DEL USUARIO
				$permisosUsuario = $usuario->ListarPermisosUsuario($accesoUsuario->idusuario);

				//ALMACENAMOS LOS PERMISOS EN UN ARRAY
				$permisos=array();

				while ($per = $permisosUsuario->fetch_object()) {
					array_push($permisos, $per->idpermiso);
				}


				//DETERMINAMOS LOS ACCESOS DEL USUARIO
				in_array(1,$permisos)?$_SESSION['escritorio']=1:$_SESSION['escritorio']=0;
				in_array(2,$permisos)?$_SESSION['almacen']=1:$_SESSION['almacen']=0;
				in_array(3,$permisos)?$_SESSION['compras']=1:$_SESSION['compras']=0;
				in_array(4,$permisos)?$_SESSION['ventas']=1:$_SESSION['ventas']=0;
				in_array(5,$permisos)?$_SESSION['acceso']=1:$_SESSION['acceso']=0;
				in_array(6,$permisos)?$_SESSION['consultac']=1:$_SESSION['consultac']=0;
				in_array(7,$permisos)?$_SESSION['consultav']=1:$_SESSION['consultav']=0;
			}
			echo json_encode($accesoUsuario);
			//echo json_encode($permisos);

		break;

		case 'salir':

		//LIMPIAMOS TODAS LAS VARIABLES DE SESION 
		session_unset();

		//DESTRUIMOS LA SESION
		session_destroy();

		//REDIRECCIONAMOS AL LOGIN
		header("Location: ../index.php");  
		break; 


}