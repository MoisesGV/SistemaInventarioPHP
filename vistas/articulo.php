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
  if ($_SESSION['almacen']==1) {

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
                          <h1 class="box-title">Articulos

                            <button id="btnAgregar"class="btn btn-success" 
                                    onclick="MostrarFormulario(true)">
                              <i class="fa fa-plus-circle"></i> 
                              Agregar
                            </button>
                            <a href="../reportes/reporteArticulo.php" target="_blank">
                              <button class="btn btn-info">Reporte</button>
                            </a>
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
                                <th>Categoria</th>
                                <th>Código</th>
                                <th>Stock</th>
                                <th>Imagen</th>
                                <th>Estado</th>
                            </thead>

                            <tbody>   
                            </tbody>

                            <tfoot>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Categoria</th>
                                <th>Código</th>
                                <th>Stock</th>
                                <th>Imagen</th>
                                <th>Estado</th>
                            </tfoot>
                        </table>

                    </div>

                    <div class="panel-body" style="height: 400px;" 
                         id="formularioRegistro">

                        <form name="formulario"
                              id="formulario"
                              method="POST">

                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Nombre <span>(*)</span></label>
                              <input type="hidden" 
                                     name="idarticulo" 
                                     id="idarticulo">
                              <input type="text"
                                     class="form-control" 
                                     name="nombre" 
                                     id="nombre"
                                     maxlength="100"
                                     placeholder="Nombre"
                                     required>
                            </div> 

                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Categoria <span>(*)</span></label>
                              <select id="idcategoria" 
                                      name="idcategoria" 
                                      class="form-control selectpicker"
                                      data-live-search="true"
                                      required>
                                
                              </select>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Stock <span>(*)</span></label>
                              <input type="number"
                                     class="form-control" 
                                     name="stock" 
                                     id="stock"
                                     required>
                            </div>
 
                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Descripción</label>
                              <input type="text" 
                                     class="form-control"
                                     name="descripcion" 
                                     id="descripcion"
                                     maxlength="256"
                                     placeholder="Descripción">
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Imagen</label>
                              <input type="file" 
                                     class="form-control"
                                     name="imagen"
                                     id="imagen">
                              <input type="hidden" 
                                     name="imagenactual" 
                                     id="imagenactual">
                              <img src="" width="150px" height="120px" id="imagenmuestra">
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                              <label>Código</label>
                              <input type="text" 
                                     class="form-control"
                                     name="codigo" 
                                     id="codigo"
                                     placeholder="Código de Barras ">
                              <button class="btn btn-success" type="button" onclick="GenerarBarCode()">Generar Código de Barras</button>
                              <button class="btn btn-info" type="button" onclick="ImprimirCodeArticulo()"><i class="fa fa-print"></i> Imprimir</button>
                              <div id="print">
                                <svg id="barcode"></svg>
                              </div>
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
<!--AGREGAR LIBRERIA PARA GENERAR CODIGO DE BARRA DE LOS ARTICULOS; DEBE AGREGARSE LA LIBRERIA ANTES DEL ARCHIVO ARTICULO.JS-->
<script src="../public/js/JsBarcode.all.min.js" type="text/javascript"></script>
<!--AGREGAR LIBRERIA PARA IMPRIMIR UNA SECCION DE MI CODIGO HTML-->
<script src="../public/js/jquery.PrintArea.js" type="text/javascript"></script>
<script src="scripts/articulo.js" type="text/javascript"></script>

<?php 
  } 
  ob_end_flush();
?>