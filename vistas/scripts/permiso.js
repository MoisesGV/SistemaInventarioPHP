;
var tabla;

//FUNCION QUE SE EJECUTA AL INICIO

function InitPermiso()
{
  MostrarFormulario(false);
  ListarPermiso();
}


//FUNCION MOSTRAR FORMULARIO

function MostrarFormulario(mostrar)
{

  if(mostrar){ //SI MOSTRAR = TRUE 
    $("#listadoRegistros").hide(); //OCULTO EL LISTADO DE LOS REGISTROS Y MUESTRO EL FORMULARIO
    $("#formularioRegistro").show();
    $("#btnAgregar").hide();
    $("#btnGuardar").prop("disabled",false); // ACTIVO EL BTN PARA GUARDAR
    $("#btnAgregar").prop("disabled",true);
  }

  else{
    $("#listadoRegistros").show();
    $("#formularioRegistro").hide();
    $("#btnAgregar").hide();
    $("#btnGuardar").prop("disabled",true);
    $("#btnAgregar").prop("disabled",false);
  }
}

//FUNCION LISTAR
function ListarPermiso()
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
      url : '../ajax/permiso.php?op=listar',
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

InitPermiso();