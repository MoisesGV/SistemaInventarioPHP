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

  //VALIDAMOS QUE EL USUARIO TENGA ACCESO AL MÓDULO CATEGORIA
  if ($_SESSION['consultav']==1) {

  date_default_timezone_set('America/Caracas');
    
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
                            <h1 class="box-title">Consulta de Ventas</h1>

                          <div class="box-tools pull-right">
                          </div>
                      </div>
                      <!-- /.box-header -->
                      <!-- centro -->
                      <div class="panel-body table-responsive" 
                           id="listadoRegistros">
                          
                          <div class="form-group col-lg-3 col-md-3 col-sm-6">
                            <label>Fecha de Inicio</label>
                            <input type="date" class="form-control" name="fechaInicio" id="fechaInicio" value="<?php echo date("Y-m-d");?>">
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-6">
                            <label>Fecha Fin</label>
                            <input type="date" class="form-control" name="fechaFin" id="fechaFin" value="<?php echo date("Y-m-d");?>">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-12">
                            <label>Cliente</label>
                            <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div>
                          <div>
                          <button class="btn btn-success" onclick="ListarConsultasVentas()">Buscar</button>
                          </div>
                          <hr>
                          <table class="table table-striped, table-bordered table-condensed, table-hover" 
                                 id="tblListado">
                              <thead>
                                  <th>Fecha</th>
                                  <th>Usuario</th>
                                  <th>Cliente</th>
                                  <th>Comprobante</th>
                                  <th>Numero</th>
                                  <th>Total</th>
                                  <th>Impuesto</th>
                                  <th>Estado</th>
                              </thead>

                              <tbody>   
                              </tbody>

                              <tfoot>
                                  <th>Fecha</th>
                                  <th>Usuario</th>
                                  <th>Cliente</th>
                                  <th>Comprobante</th>
                                  <th>Numero</th>
                                  <th>Total</th>
                                  <th>Impuesto</th>
                                  <th>Estado</th>
                              </tfoot>
                          </table>

                      </div>                
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
  <script src="scripts/consultaVentas.js" type="text/javascript"></script>

<?php 
} 
  ob_end_flush();
?>
