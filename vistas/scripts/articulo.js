;
var tabla;

//FUNCION QUE SE EJECUTA AL INICIO
function InitArticulo()
{
	MostrarFormulario(false);
	ListarArticulo();

	$("#formulario").on("submit",function(e){
		GuardaryEditarArticulo(e);
	})

	//CARGAMOS LAS OPCIONES DEL SELECT CON LAS CATEGORIA
	$.post("../ajax/articulo.php?op=selectCategoria", function(r){
		$("#idcategoria").html(r);
		$("#idcategoria").selectpicker('refresh')

	});
	$("#imagenmuestra").hide();
}

//LIMPIAR INPUTS DEL FORMULARIO
function LimpiarArticulo()
{
	$("#idarticulo").val("");
	$("#codigo").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
	$("#stock").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#print").hide();

}

//FUNCION MOSTRAR FORMULARIO AGREGAR ARTICULO

function MostrarFormulario(mostrar)
{
	LimpiarArticulo();

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
	LimpiarArticulo();
	MostrarFormulario(false);
}

//FUNCION LISTAR
function ListarArticulo()
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
			url : '../ajax/articulo.php?op=listar',
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

function GuardaryEditarArticulo(e)
{
	e.preventDefault(); //NO SE ACTIVARA LA ACCION PREDETERMINADA DEL EVENTO 
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/articulo.php?op=guardaryeditar",
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
	LimpiarArticulo();
}

//MOSTRAR 
function MostrarArticuloFormulario(idarticulo)
{
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo}, function(data, status){

		data=JSON.parse(data); //LA FILA OBTENIDA DE AJAX/CATEGORIA.PHP LA CONVERTIMOS EN UN OBJ QUE PUEDE SER LEIDO POR JS
		MostrarFormulario(true);
		//console.log(idarticulo)


		$("#idarticulo").val(data.idarticulo);
		$("#idcategoria").val(data.idcategoria);

		//IMPORTANTE AGREGAR EL REFRESH PARA 'REFRESCAR EL SELECT', Y MOSTRAR LA CATEGORIA QUE TIENE SELECCIONADA UN OBJETO
		$("#idcategoria").selectpicker('refresh');
		$("#codigo").val(data.codigo);
		$("#nombre").val(data.nombre);
		$("#stock").val(data.stock);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);
		$("#imagenactual").val(data.imagen);
		$("#descripcion").val(data.descripcion);
		GenerarBarCode();
		

	})
}

function DesactivarArticulo(idarticulo)
{
	bootbox.confirm("¿Esta seguro que el Artículo se encuentra Agotado?", function(result){
		if(result){

			$.post("../ajax/articulo.php?op=desactivar",{idarticulo : idarticulo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function ActivarArticulo(idarticulo)
{
	bootbox.confirm("¿Esta seguro que el Artículo se encuentra Disponible?", function(result){
		if(result){

			$.post("../ajax/articulo.php?op=activar",{idarticulo : idarticulo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

//GENERAR CÓDIGO DE BARRA
function GenerarBarCode(){
	codigo = $("#codigo").val();
	JsBarcode("#barcode", codigo);
	$("#print").show();
}

//IMPRIMIR CODIGO DE BARRAS
function ImprimirCodeArticulo(){
	$("#print").printArea();
}

InitArticulo();