<?php
//ACTIVAMOS ALMACENAMIENTO EN EL BUFFER
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  
  header("Location: login.html");
}
else
{
  require 'header.php';
  
  //VALIDAMOS QUE EL USUARIO TENGA ACCESO AL MÓDULO PERMISO
  if ($_SESSION['acceso']==1) {

?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Permisos

                            <button id="btnAgregar"class="btn btn-success" 
                                    onclick="MostrarFormulario(true)">
                              <i class="fa fa-plus-circle"></i> 
                              Agregar
                            </button>

                          </h1>

                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" 
                         id="listadoRegistros">
                        
                        <table class="table table-striped, table-bordered table-condensed, table-hover" 
                               id="tblListado">
                            <thead>
                                <th>Nombre</th>
                            </thead>

                            <tbody>   
                            </tbody>

                            <tfoot>
                                <th>Nombre</th>
                            </tfoot>
                        </table>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
}
  else
  {
    require 'noAcceso.php';
  }
require 'footer.php';
?>
<script src="scripts/permiso.js" type="text/javascript"></script>
<?php 
  } 
  ob_end_flush();
?>