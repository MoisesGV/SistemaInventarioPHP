;
var tabla;

//FUNCION QUE SE EJECUTA AL INICIO

function InitCategoria()
{
	MostrarFormulario(false);
	ListarCategoria();

	$("#formulario").on("submit",function(e){
		GuardaryEditarCategoria(e);
	})
}

//LIMPIAR INPUTS DEL FORMULARIO

function LimpiarCategoria()
{
	$("#idcategoria").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
}

//FUNCION MOSTRAR FORMULARIO

function MostrarFormulario(mostrar)
{
	LimpiarCategoria();

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
	LimpiarCategoria();
	MostrarFormulario(false);
}

//FUNCION LISTAR
function ListarCategoria()
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
			url : '../ajax/categoria.php?op=listar',
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

function GuardaryEditarCategoria(e)
{
	e.preventDefault(); //NO SE ACTIVARA LA ACCION PREDETERMINADA DEL EVENTO 
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/categoria.php?op=guardaryeditar",
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
	LimpiarCategoria();
}

function MostrarCategoriaFormulario(idcategoria)
{
	$.post("../ajax/categoria.php?op=mostrar",{idcategoria : idcategoria}, function(data, status){

		data=JSON.parse(data); //LA FILA OBTENIDA DE AJAX/CATEGORIA.PHP LA CONVERTIMOS EN UN OBJ QUE PUEDE SER LEIDO POR JS
		MostrarFormulario(true);
		console.log(idcategoria)

		$("#idcategoria").val(data.idcategoria);
		$("#nombre").val(data.nombre);
		$("#descripcion").val(data.descripcion);

	})
}

function DesactivarCategoria(idcategoria)
{
	bootbox.confirm("¿Esta seguro que desea desactivar esta Categoria?", function(result){
		if(result){

			$.post("../ajax/categoria.php?op=desactivar",{idcategoria : idcategoria}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function ActivarCategoria(idcategoria)
{
	bootbox.confirm("¿Esta seguro que desea activar esta Categoria?", function(result){
		if(result){

			$.post("../ajax/categoria.php?op=activar",{idcategoria : idcategoria}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

InitCategoria();