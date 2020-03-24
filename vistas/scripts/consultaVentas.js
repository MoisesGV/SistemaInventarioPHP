;
var tabla;

//FUNCION QUE SE EJECUTA AL INICIO

function InitConsultasVentas()
{
	ListarConsultasVentas();
	//CARGAMOS LOS DATOS DEL SELECT CON LOS CLIENTES
	$.post("../ajax/ventas.php?op=listarClientes", function(e){
		//console.log(e)
		$("#idcliente").html(e);
		$("#idcliente").selectpicker('refresh');
	})

}

//FUNCION LISTAR
function ListarConsultasVentas()
{
	var fechaInicio = $("#fechaInicio").val();
	var fechaFin = $("#fechaFin").val();
	var idcliente = $("#idcliente").val();

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
			url : '../ajax/consultas.php?op=ventasFecha',
			data:{fechaInicio: fechaInicio, fechaFin: fechaFin, idcliente: idcliente},
			type : "get",
			datatype : "json",
			error: function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength": 10, //NUMERO DE REGISTROS(PAGINACION)
		"order": [[0,"desc"]]

	}).DataTable();
}

InitConsultasVentas();