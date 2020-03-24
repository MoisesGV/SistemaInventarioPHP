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
  
  //VALIDAMOS QUE EL USUARIO TENGA ACCESO AL MÓDULO PROVEEDORES
  if ($_SESSION['compras']==1) {

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
                          <h1 class="box-title">Proveedores 

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
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Número de Documento</th>
                                <th>Telefono</th>
                                <th>Email</th>
                            </thead>

                            <tbody>   
                            </tbody>

                            <tfoot>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Número de Documento</th>
                                <th>Telefono</th>
                                <th>Email</th>
                            </tfoot>
                        </table>

                    </div>

                    <div class="panel-body" style="height: 400px;" 
                         id="formularioRegistro">

                        <form name="formulario"
                              id="formulario"
                              method="POST">

                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Nombre</label>
                              <input type="hidden" 
                                     name="idpersona" 
                                     id="idpersona">
                              <input type="hidden" 
                                     name="tipo_persona" 
                                     id="tipo_persona" value="Proveedor">
                              <input type="text"
                                     class="form-control" 
                                     name="nombre" 
                                     id="nombre"
                                     maxlength="100"
                                     placeholder="Nombre"
                                     required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Tipo Documento</label>
                              <select name="tipo_documento" 
                                      class="form-control select-picker"
                                      id="tipo_documento" required>
                                <option value="Venezolano">V</option>
                                <option value="Extranjero">E</option>
                                <option value="RIF">J</option>
                              </select>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Número Documento:</label>
                              <input type="text"
                                     class="form-control" 
                                     name="num_documento" 
                                     id="num_documento"
                                     maxlength="20"
                                     placeholder="Documento"
                                     required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Dirección</label>
                              <input type="text"
                                     class="form-control" 
                                     name="direccion" 
                                     id="direccion"
                                     maxlength="70"
                                     placeholder="Dirección">
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Teléfono</label>
                              <input type="text"
                                     class="form-control" 
                                     name="telefono" 
                                     id="telefono"
                                     maxlength="20"
                                     placeholder="Teléfono">
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Email</label>
                              <input type="email"
                                     class="form-control" 
                                     name="email" 
                                     id="email"
                                     maxlength="50"
                                     placeholder="Email">
                            </div>



                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                              <button class="btn btn-success" type="submit" id="btnGuardar">
                                <i class="fa fa-save"></i>
                                Guardar
                              </button>
                              <button class="btn btn-danger" type="button" onclick="CancelarFormulario()">
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
<?php
}
  else
  {
    require 'noAcceso.php';
  }
require 'footer.php';
?>
<script src="scripts/proveedor.js" type="text/javascript"></script>
<?php 
  } 
  ob_end_flush();
?>