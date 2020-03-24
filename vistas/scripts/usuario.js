;
var tabla;

//FUNCION QUE SE EJECUTA AL INICIO
function InitUsuario()
{
	MostrarFormulario(false);
	ListarUsuario();

	$("#formulario").on("submit",function(e){
		GuardaryEditarUsuario(e);
	})
	$("#imagenmuestra").hide();
	//MOSTRAMOS LOS PERMISOS
	$.post("../ajax/usuario.php?op=permisos&id", function(e){
		$("#permisos").html(e);
		$("#permisos").children().prop('checked', false);
	})
}

//LIMPIAR INPUTS DEL FORMULARIO
function LimpiarUsuario()
{
	$("#idusuario").val("");
	$("#nombre").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#cargo").val("");
	$("#login").val("");
	$("#clave").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");

}

//FUNCION MOSTRAR FORMULARIO AGREGAR ARTICULO

function MostrarFormulario(mostrar)
{
	LimpiarUsuario();

	if(mostrar){ //SI MOSTRAR = TRUE 
		$("#listadoRegistros").hide(); //OCULTO EL LISTADO DE LOS REGISTROS Y MUESTRO EL FORMULARIO
		$("#formularioRegistro").show();
		$("#btnGuardar").prop("disabled",false); // ACTIVO EL BTN PARA GUARDAR
		$("#btnAgregar").prop("disabled",true);
		$("#imagenmuestra").hide();
	}

	else{
		$("#listadoRegistros").show();
		$("#formularioRegistro").hide();
		$("#btnGuardar").prop("disabled",true);
		$("#btnAgregar").prop("disabled",false);

	}
}

//CANCELAR FORMULARIO
function CancelarFormulario()
{
	LimpiarUsuario();
	MostrarFormulario(false);
}

//FUNCION LISTAR
function ListarUsuario()
{
	tabla = $("#tblListado").dataTable({

		"aProcessing": true, //ACTIVAR EL PROCESAMIENTO DEL DATATABLE
		"aServerSide": true, //PAGINACION Y FILTRADO REALIZADOS POR EL SERVIDOR
		dom: 'Bfrtip', //DEFINIMOS EL ELEMENTO DE CONTROL DE LA TABLA
		buttons: [
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax":
		{
			url : '../ajax/usuario.php?op=listar',
			type : "get",
			datatype : "json",
			error: function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength": 5, //NUMERO DE REGISTROS(PAGINACION)
		"order": [[0,"desc"]] //ORDENAR (columna,orden)

	}).DataTable();
}

function GuardaryEditarUsuario(e)
{
	e.preventDefault(); //NO SE ACTIVARA LA ACCION PREDETERMINADA DEL EVENTO 
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/usuario.php?op=guardaryeditar",
		type : "POST",
		data: formData, //OBTIENE TODOS LOS DATOS DEL FORMULARIO QUE ESTAN ALMACENADOS EN formData
		contentType: false,
		processData: false,

		success: (datos)=>{
			bootbox.alert(datos); //EL MENSAJE DEL ALERT LO GENERA EL ARCHIVO Ajax/categoria.php
							// LO ALMACENADO EN LA VARIABLE $res
			MostrarFormulario(false);
			tabla.ajax.reload();
		}
	});
	LimpiarUsuario();
}

//MOSTRAR 
function MostrarUsuarioFormulario(idusuario)
{
	$.post("../ajax/usuario.php?op=mostrar",{idusuario : idusuario}, function(data, status){

		data=JSON.parse(data); //LA FILA OBTENIDA DE AJAX/CATEGORIA.PHP LA CONVERTIMOS EN UN OBJ QUE PUEDE SER LEIDO POR JS
		MostrarFormulario(true);
		//console.log(idarticulo)

		$("#idusuario").val(data.idusuario);
		$("#nombre").val(data.nombre);
		$("#tipo_documento").val(data.tipo_documento);
		$("#tipo_documento").selectpicker('refresh');
		$("#num_documento").val(data.num_documento);
		$("#direccion").val(data.direccion);
		$("#telefono").val(data.telefono);
		$("#email").val(data.email);
		$("#cargo").val(data.cargo);
		$("#login").val(data.login);
		$('#clave').removeAttr('required');
		$("#cargo").val(data.cargo);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/usuarios/"+data.imagen);
		$("#imagenactual").val(data.imagen);
		

	});
	$.post("../ajax/usuario.php?op=permisos&id="+idusuario, function(e){
		$("#permisos").html(e);
	});
 
}
 
function DesactivarUsuario(idusuario)
{
	console.log(idusuario)
	bootbox.confirm("¿Esta seguro que desea Desactivar este Usuario?", function(result){
		if(result){

			$.post("../ajax/usuario.php?op=desactivar",{idusuario : idusuario}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function ActivarUsuario(idusuario)
{	
	console.log(idusuario)
	bootbox.confirm("¿Esta seguro que desea Activar este Usuario?", function(result){
		if(result){

			$.post("../ajax/usuario.php?op=activar",{idusuario : idusuario}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

InitUsuario();