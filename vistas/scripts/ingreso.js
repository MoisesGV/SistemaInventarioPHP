;
var tabla;

//FUNCION QUE SE EJECUTA AL INICIO
function InitIngreso()
{
	MostrarFormulario(false);
	ListarIngreso();

	$("#formulario").on("submit",function(e){
		GuardarIngreso(e);
	});

	//CARGAMOS LOS DATOS DEL SELECT CON LOS PROVEEDORES
	$.post("../ajax/ingreso.php?op=listarProveedores", function(e){
		$("#idproveedor").html(e);
		$("#idproveedor").selectpicker('refresh');
	})
}

//LIMPIAR INPUTS DEL FORMULARIO
function LimpiarIngreso()
{
	$("#idproveedor").val("");
	$("#proveedor").val("");
	$("#serie_comprobante").val("");
	$("#num_comprobante").val("");
	$("#fecha_hora").val("");
	$("#impuesto").val("18");

	$("#total_compra").val("");
	$(".filas").remove();
	$("#total").html("0");

	//MARCAMOS EL PRIMER TIPO_DOCUMENTO
	$("#tipo_comprobante").val('Factura');
	$("#tipo_comprobante").selectpicker('refresh');

	//AGREGAR POR DEFECTO LA FECHA ACTUAL PARA LOS INGRESOS
	var fecha = new Date();
	var dia = ("0" + fecha.getDate()).slice(-2);
	var mes = ("0" + (fecha.getMonth() + 1 )).slice(-2);
	var fechaActual = fecha.getFullYear()+"-"+(mes)+"-"+(dia);
	$("#fecha_hora").val(fechaActual);

}

//FUNCION MOSTRAR FORMULARIO AGREGAR ARTICULO

function MostrarFormulario(mostrar)
{
	LimpiarIngreso();

	if(mostrar){ //SI MOSTRAR = TRUE 
		$("#listadoRegistros").hide(); //OCULTO EL LISTADO DE LOS REGISTROS Y MUESTRO EL FORMULARIO
		$("#formularioRegistro").show();
		$("#btnAgregar").prop("disabled",true);

		ListarArticulos();
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		detalles=0; //RESET LA VARIABLE DETALLES 
		$("#btnAgregarArticulo").show();
	}

	else{
		$("#listadoRegistros").show();
		$("#formularioRegistro").hide();
		$("#btnGuardar").prop("disabled",false);
		$("#btnAgregar").show();
		$("#btnAgregar").prop("disabled",false);
	}
}

//CANCELAR FORMULARIO
function CancelarFormulario()
{
	LimpiarIngreso();
	MostrarFormulario(false);
}

//FUNCION LISTAR
function ListarIngreso()
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
			url : '../ajax/ingreso.php?op=listar',
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

//FUNCION LISTAR ARTICULOS
function ListarArticulos()
{
	tabla = $("#tblArticulos").dataTable({

		"aProcessing": true, //ACTIVAR EL PROCESAMIENTO DEL DATATABLE
		"aServerSide": true, //PAGINACION Y FILTRADO REALIZADOS POR EL SERVIDOR
		dom: 'Bfrtip', //DEFINIMOS EL ELEMENTO DE CONTROL DE LA TABLA
		buttons: [
			
		],
		"ajax":
		{
			url : '../ajax/ingreso.php?op=listarArticulos',
			type : "get",
			datatype : "json",
			error: function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength": 4, //NUMERO DE REGISTROS(PAGINACION)
		"order": [[0,"desc"]] //ORDENAR (columna,orden)

	}).DataTable();
}


function GuardarIngreso(e)
{
	e.preventDefault(); //NO SE ACTIVARA LA ACCION PREDETERMINADA DEL EVENTO 
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/ingreso.php?op=guardar",
		type : "POST",
		data: formData, //OBTIENE TODOS LOS DATOS DEL FORMULARIO QUE ESTAN ALMACENADOS EN formData
		contentType: false,
		processData: false,

		success: (datos)=>{
			bootbox.alert(datos); //EL MENSAJE DEL ALERT LO GENERA EL ARCHIVO Ajax/categoria.php
							// LO ALMACENADO EN LA VARIABLE $res
			MostrarFormulario(false);
			ListarIngreso();
		}
	});
	LimpiarIngreso();
}

//MOSTRAR 
function MostrarIngresoFormulario(idingreso)
{
	$.post("../ajax/ingreso.php?op=mostrar",{idingreso : idingreso}, function(data, status){

		data=JSON.parse(data); //LA FILA OBTENIDA DE AJAX/INGRESO.PHP LA CONVERTIMOS EN UN OBJ QUE PUEDE SER LEIDO POR JS
		MostrarFormulario(true);

		$("#idproveedor").val(data.idproveedor);
		$("#idproveedor").selectpicker('refresh');
		$("#tipo_comprobante").val(data.tipo_comprobante);
		$("#tipo_comprobante").selectpicker('refresh');
		$("#serie_comprobante").val(data.serie_comprobante);
		$("#num_comprobante").val(data.num_comprobante);
		$("#fecha_hora").val(data.fecha);
		$("#impuesto").val(data.impuesto);
		$("#idingreso").val(data.idingreso);

		//OCULTAR Y MOSTRAR BOTONES
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArticulo").hide();
	});

	$.post("../ajax/ingreso.php?op=mostrarDetallesIngreso&id="+idingreso, function(e){
		$("#detalleIngreso").html(e); 
	})
}

function AnularIngreso(idingreso)
{
	bootbox.confirm("¿Esta seguro que desea Anular esta Compra?", function(result){
		if(result){

			$.post("../ajax/ingreso.php?op=anular",{idingreso : idingreso}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

//DECLARACIÓN DE VARIABLES PARA TRABAJAR CON LAS COMPRAS Y SUS DETALLES

var impuesto=18;
var cont=0;
var detalles=0;
$("#btnGuardar").hide();
$("#tipo_comprobante").change(MarcarImpuesto);

function MarcarImpuesto()
{
	
	var tipo_comprobante=$("#tipo_comprobante option:selected").text();
	
	//SI SE SELECCIONA EL TIPO COMPROBANTE = FACTURA SE LE APLICA EL IMPUESTO
	if(tipo_comprobante=='Factura')
	{

		$("#impuesto").val(impuesto);

	}else{

		$("#impuesto").val("0");

	}
}

function AgregarDetalle(idarticulo, articulo)
{

	var cantidad=1;
	var precio_compra=1;
	var precio_venta=1;

	if(idarticulo!=''){

		var subtotal=cantidad*precio_compra;

		var fila='<tr class="filas" id="fila'+cont+'">'+
					'<td> <button type="button" class="btn btn-danger"onclick="EliminarDetalle('+cont+')"><i class="fa fa-close"></i></button></td>'+
					'<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
					'<td><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
					'<td><input type="number" name="precio_compra[]" id="precio_compra[]" value="'+precio_compra+'"></td>'+
					'<td><input type="number" name="precio_venta[]" value="'+precio_venta+'"></td>'+
					'<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
					'<td><button type="button" onclick="ActualizarSubtotales()" class="btn btn-success"><i class="fa fa-refresh"></i></button></td>'+
					' </tr>';

		cont++;
		detalles=detalles+1;
		$('#detalleIngreso').append(fila);
		ActualizarSubtotales() 

	}else{
		alert('Error al registrar el detalle')
	}
}

function ActualizarSubtotales()
{

	//ALMACENAMOS EN ARRAYS, LOS LA CANTIDAD, PRECIO DE COMPRA Y SUBTOTAL DE CADA UNO DE LOS ELEMENTOS QUE AGREGEMOS AL INGRESO
	
	var cantidad = document.getElementsByName('cantidad[]');
	var precio_compra = document.getElementsByName('precio_compra[]');
	var subtotal = document.getElementsByName('subtotal');

	//CON UN BUCLE FOR, ACTUALIZAMOS  CADA SUBTOTAL, UTILIZANDO LA CANTIDAD Y EL PRECIO DE COMPRA DE CADA FILA

	for (var i = 0; i < cantidad.length; i++) {
		
		var inputCantidad = cantidad[i];
		var inputPrecio_compra = precio_compra[i];
		var inputSubtotal = subtotal[i];

		inputSubtotal.value = inputCantidad.value * inputPrecio_compra.value;
		document.getElementsByName('subtotal')[i].innerHTML = inputSubtotal.value;
	}
	CalcularTotal();
}

function CalcularTotal()
{
	var subtotal = document.getElementsByName('subtotal');
	var total = 0.0;

	for (var i = 0; i < subtotal.length; i++) {
		total += document.getElementsByName('subtotal')[i].value;
	}
	$("#total").html("Bs. " + total);
	$("#total_compra").val(total);
	EvaluarIngreso();
}

//VERIFICAMOS QUE EXISTA AL MENOS UN DETALLE PARA MOSTRAR BOTONES DE GUARDAR Y CANCELAR
function EvaluarIngreso()
{
	if (detalles>0) {
		$('#btnGuardar').show();
	}
	else{
		$('#btnGuardar').hide();
		cont=0;
	}
}

function EliminarDetalle(indice)
{
	$("#fila"+ indice).remove();
	CalcularTotal();
	detalles=detalles-1;
}


InitIngreso();