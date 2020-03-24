;
var tabla;

//FUNCION QUE SE EJECUTA AL INICIO

function InitConsultasCompras()
{
	ListarConsultasCompras();
	$("#fechaInicio").change(ListarConsultasCompras)	
	$("#fechaFin").change(ListarConsultasCompras)

}

//FUNCION LISTAR
function ListarConsultasCompras()
{
	var fechaInicio = $("#fechaInicio").val();
	var fechaFin = $("#fechaFin").val();

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
			url : '../ajax/consultas.php?op=comprasFecha',
			data:{fechaInicio: fechaInicio, fechaFin: fechaFin},
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

InitConsultasCompras();