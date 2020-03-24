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
  if ($_SESSION['ventas']==1) {
    
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
                            <h1 class="box-title">Ventas

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
                                  <th>Opciones</th>
                                  <th>Fecha</th>
                                  <th>Cliente</th>
                                  <th>Usuario</th>
                                  <th>Documento</th>
                                  <th>Numero</th>
                                  <th>Total Venta</th>
                                  <th>Estado</th>
                              </thead>

                              <tbody>   
                              </tbody>

                              <tfoot>
                                  <th>Opciones</th>
                                  <th>Fecha</th>
                                  <th>Cliente</th>
                                  <th>Usuario</th>
                                  <th>Documento</th>
                                  <th>Numero</th>
                                  <th>Total Venta</th>
                                  <th>Estado</th>
                              </tfoot>
                          </table>

                      </div>

                      <div class="panel-body" style="height: 400px;" 
                           id="formularioRegistro">

                          <form name="formulario"
                                id="formulario"
                                method="POST">

                              <div class="form-group col-lg-8 col-md-8 col-xs-12">
                                <label>Cliente</label>
                                <input type="hidden" 
                                       name="idventa" 
                                       id="idventa">
                                <select id="idcliente" 
                                        name="idcliente" 
                                        class="form-control selectpicker" 
                                        data-live-search="true" required>
                                  

                                </select>
                              </div>

                              <div class="form-group col-lg-4 col-md-4 col-xs-12">
                                <label>Fecha</label>
                                <input type="date" 
                                       class="form-control"
                                       name="fecha_hora" 
                                       id="fecha_hora"
                                       required>
                              </div>

                              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                <label>Tipo de Comprobante (*)</label>
                                <select id="tipo_comprobante" 
                                        name="tipo_comprobante" 
                                        class="form-control selectpicker" 
                                        data-live-search="true" required>
                                        <option value="Factura">Factura</option>
                                        <option value="Ticket">Ticket</option>
                                </select>
                              </div>

                              <div class="form-group col-lg-2 col-md-2 col-xs-6">
                                <label>Serie:</label>
                                <input type="text" 
                                       class="form-control"
                                       name="serie_comprobante" 
                                       id="serie_comprobante"
                                       maxlength="7"
                                       placeholder="Serie Comprobante" 
                                       required>
                              </div>

                              <div class="form-group col-lg-2 col-md-2 col-xs-6">
                                <label>Numero:</label>
                                <input type="text" 
                                       class="form-control"
                                       name="num_comprobante" 
                                       id="num_comprobante"
                                       maxlength="10"
                                       placeholder="Numero Comprobante" 
                                       required>
                              </div>

                              <div class="form-group col-lg-2 col-md-2 col-xs-6">
                                <label>Impuesto:</label>
                                <input type="text" 
                                       class="form-control"
                                       name="impuesto" 
                                       id="impuesto"
                                       placeholder="Impuesto" 
                                       required>
                              </div>

                              <div class="form-group col-lg-3 col-md-3 col-xs-12">
                                <a data-toggle="modal" href="#ModalArticulos">
                                  <button type="button" id="btnAgregarArticulo" class="btn btn-success"><span class="fa fa-plus"></span>Agregar Artículo</button>
                                </a>          
                              </div>
                              <div class="col-md-12 col-xm-12">
                                <table id="detalleVenta" class="table table-striped table-bordered table-condensed table-hover">
                                  <thead style="background-color: #1e282c; color:#d3d3d3">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                  </thead>
                                  <tbody>
                                    
                                  </tbody>
                                  <tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">00.0</h4><input type="hidden" name="total_venta" id="total_venta"></th>
                                  </tfoot>
                                  
                                </table>
                                
                              </div>

                              <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                <button class="btn btn-success" type="submit" id="btnGuardar">
                                  <i class="fa fa-save"></i>
                                  Guardar
                                </button>
                                <button class="btn btn-danger" id="btnCancelar" type="button" onclick="CancelarFormulario()">
                                  <i class="fa fa-arrow-circle-left"></i>
                                  Cancelar
                                </button>
                              </div>
                          </form>
                      </div>
                      <!--Fin centro -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->

      </div><!-- /.content-wrapper -->
    <!--Fin-Contenido-->

    <!--INICIO VENTANA MODAL-->
    <div class="modal fade" id="ModalArticulos" tabindex="-1" role="dialog" aria-labelledby="ModalArticulosLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 60% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
            <h4 class="modal-tittle">Seleccione un Artículo</h4>
          </div>
          <div class="modal-body">
            <table id="tblArticulos" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Codigo</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>Imagen</th>
              </thead>
              <tbody>
                
              </tbody>
              <tfoot>
              </tfoot>
            </table>
            
          </div>
          <div class="modal-footer">
            
          </div>
        </div>
        
      </div>
    </div>
    <!--FIN VENTANA MODAL-->
  <?php
  }
  else
  {
    require 'noAcceso.php';
  }
  require 'footer.php';
  ?>
  <script src="scripts/ventas.js" type="text/javascript"></script>

<?php 
} 
  ob_end_flush();
?>
