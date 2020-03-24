;
var tabla;

//FUNCION QUE SE EJECUTA AL INICIO

function InitCliente()
{
	MostrarFormulario(false);
	ListarCliente();

	$("#formulario").on("submit",function(e){
		GuardaryEditarCliente(e);
	})
}

//LIMPIAR INPUTS DEL FORMULARIO

function LimpiarCliente()
{
	$("#idpersona").val("");
	$("#nombre").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
}

//FUNCION MOSTRAR FORMULARIO

function MostrarFormulario(mostrar)
{
	LimpiarCliente();

	if(mostrar){ //SI MOSTRAR = TRUE 
		$("#listadoRegistros").hide(); //OCULTO EL LISTADO DE LOS REGISTROS Y MUESTRO EL FORMULARIO
		$("#formularioRegistro").show();
		$("#btnGuardar").prop("disabled",false); // ACTIVO EL BTN PARA GUARDAR
		$("#btnAgregar").prop("disabled",true);
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
	LimpiarCliente();
	MostrarFormulario(false);
}

//FUNCION LISTAR
function ListarCliente()
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
			url : '../ajax/persona.php?op=listarClientes',
			type : "get",
			datatype : "json",
			error: function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength": 5, //NUMERO DE REGISTROS(PAGINACION)
		"order": [[0,"desc"]]

	}).DataTable();
}

function GuardaryEditarCliente(e)
{
	e.preventDefault(); //NO SE ACTIVARA LA ACCION PREDETERMINADA DEL EVENTO 
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/persona.php?op=guardaryeditar",
		type : "POST",
		data: formData, //OBTIENE TODOS LOS DATOS DEL FORMULARIO QUE ESTAN ALMACENADOS EN formData
		contentType: false,
		processData: false,

		success: (datos)=>{
			bootbox.alert(datos); //EL MENSAJE DEL ALERT LO GENERA EL ARCHIVO Ajax/Proveedor.php
							// LO ALMACENADO EN LA VARIABLE $res
			MostrarFormulario(false);
			tabla.ajax.reload();
		}
	});
	LimpiarCliente();
}

function MostrarClienteFormulario(idpersona)
{
	$.post("../ajax/persona.php?op=mostrar",{idpersona : idpersona}, function(data, status){

		data=JSON.parse(data); //LA FILA OBTENIDA DE AJAX/Proveedor.PHP LA CONVERTIMOS EN UN OBJ QUE PUEDE SER LEIDO POR JS
		MostrarFormulario(true);

		$("#idpersona").val(data.idpersona);
		$("#nombre").val(data.nombre);
		$("#tipo_documento").val(data.tipo_documento);
		$("#tipo_documento").selectpicker('refresh');
		$("#num_documento").val(data.num_documento);
		$("#direccion").val(data.direccion);
		$("#telefono").val(data.telefono);
		$("#email").val(data.email);


	})
}

function EliminarCliente(idpersona)
{
	bootbox.confirm("¿Esta seguro que desea eliminar este Cliente?", function(result){
		if(result){

			$.post("../ajax/persona.php?op=eliminar",{idpersona : idpersona}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

InitCliente();