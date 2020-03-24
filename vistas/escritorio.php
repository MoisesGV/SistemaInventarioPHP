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
  if ($_SESSION['escritorio']==1) {
      
        
    require_once "../modelos/Consultas.php";
    $consultas = new Consultas();

    //GRAFICOS DE LAS COMPRAS DE LOS ULTIMOS 10 DIAS

    $resC = $consultas->ComprasUltimos10Dias();
    $fechasCompras='';
    $totalesCompras='';

    while($regC=$resC->fetch_object()){

      $fechasCompras = $fechasCompras."'".$regC->fecha."',";
      $totalesCompras = $totalesCompras.$regC->total .',';
    }

      //ELIMINAMOS LA ULTIMA COMA DEL STRING
      $fechasCompras = substr($fechasCompras, 0,-1); 
      $totalesCompras = substr($totalesCompras, 0,-1);

    //GRAFICOS DE LAS COMPRAS DE LOS ULTIMOS 10 DIAS

    $resV = $consultas->VentasUltimos10Dias();
    $fechasVentas='';
    $totalesVentas='';

    while($regV=$resV->fetch_object()){

      $fechasVentas = $fechasVentas."'".$regV->fecha."',";
      $totalesVentas = $totalesVentas.$regV->total .',';
    }

      //ELIMINAMOS LA ULTIMA COMA DEL STRING
      $fechasVentas = substr($fechasVentas, 0,-1); 
      $totalesVentas = substr($totalesVentas, 0,-1);



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
                            <h1 class="box-title">Escritorio</h1>
                            <br>
                          <div class="box-tools pull-right">
                          </div>
                      </div>
                      <!-- /.box-header -->
                      <!-- centro -->
                      <div class="panel-body">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                          <div class="small-box bg-aqua">
                            <div class="inner">
                              <h4 id="totalCompraHoy" style="font-size: 17px;"></h4>
                              <p>Compras</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-bag"></i> 
                            </div>
                            <a href="ingreso.php" class="small-box-footer">Compras<i class="fa fa-arrow-circle-right"></i></a>
                          </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6">
                          <div class="small-box bg-green">
                            <div class="inner">
                              <h4 id="totalVentaHoy" style="font-size: 17px;"></h4>
                              <p>Ventas</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-bag"></i> 
                            </div>
                            <a href="ventas.php" class="small-box-footer">Ventas<i class="fa fa-arrow-circle-right"></i></a>
                          </div>
                        </div>
                      </div>

                      <div class="panel-body">

                        <div class="col-lg-6 col-md-6 col-sm-6">
                          <div class="box box-primary">
                            <div class="box-header with-border">
                              Compras de los Últimos 10 Días
                            </div>
                            <div class="box-body">
                              <canvas id="compras" width="400" height="300"></canvas>
                            </div>
                          </div>  
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6">
                          <div class="box box-green">
                            <div class="box-header with-border">
                              Ventas de los Últimos 10 Días
                            </div>
                            <div class="box-body">
                              <canvas id="ventas" width="400" height="300"></canvas>
                            </div>
                          </div>
                        </div>

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
  <script src="../public/js/Chart.min.js" type="text/javascript"></script>
  <script src="../public/js/Chart.bundle.min.js" type="text/javascript"></script>
  <script src="scripts/estadisticas.js" type="text/javascript"></script>

  <script>
    var ctx = document.getElementById('compras');
    var myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: [<?php echo $fechasCompras;?>],
            datasets: [{
                label: '# Compras de los Últimos 10 Días',
                data: [<?php echo $totalesCompras;?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
  </script>

  <script>
    var ctx = document.getElementById('ventas');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php echo $fechasVentas;?>],
            datasets: [{
                label: '# Ventas de los Últimos 10 Días',
                data: [<?php echo $totalesVentas;?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
  </script>

<?php 
} 
  ob_end_flush();
?>
