;
function InitEstadisticas()
{
$.post("../ajax/consultas.php?op=totalCompraHoy", function(r){
		$("#totalCompraHoy").html(r);
	});
$.post("../ajax/consultas.php?op=totalVentaHoy", function(r){
		$("#totalVentaHoy").html(r);
	});
}

InitEstadisticas();