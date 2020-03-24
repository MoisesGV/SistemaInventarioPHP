<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
if ($_SESSION['ventas']==1)
{
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="../public/css/ticket.css" rel="stylesheet" type="text/css">
</head>
<body onload="window.print();">
<?php

//Incluímos la clase Venta
require_once "../modelos/Ventas.php";
//Instanaciamos a la clase con el objeto venta
$venta = new Ventas();
//En el objeto $rspta Obtenemos los valores devueltos del método ventacabecera del modelo
$rspta = $venta->CabeceraFacturaVenta($_GET["id"]);
//Recorremos todos los valores obtenidos
$reg = $rspta->fetch_object();

//Establecemos los datos de la empresa
$empresa = 'CVV CAJA VENEZOLANA DE VALORES, C.A';
$documento = 'J-30018793-4';
$direccion = 'El Rosal, Caracas';
$telefono = '0212-9526277';

?>
<div class="zona_impresion">
<!-- codigo imprimir -->
<br>
<table border="0" align="center" width="300px">
    <tr>
        <td align="center">
        <!-- Mostramos los datos de la empresa en el documento HTML -->
        <strong> <?php echo $empresa; ?></strong><br>
        <?php echo $documento.'   '.$direccion; ?><br>
        <?php echo $direccion; ?><br>
        <?php echo 'Teléfono: '.$telefono; ?><br> 
        </td>
    </tr>
    <tr>
        <td align="center"><?php echo $reg->fecha; ?></td>
    </tr>
    <tr>
      <td align="center"></td>
    </tr>
    <tr>
        <!-- Mostramos los datos del cliente en el documento HTML -->
        <td><strong>Cliente: </strong><?php echo $reg->cliente; ?></td>
    </tr>
    <tr>

        <td><strong>Identificación: </strong><?php 
            switch ($tip=$reg->tipo_documento) {
               
            
                case $tip='Venezolano':
                    echo "V -".$reg->num_documento;
                    break;
                case $tip='Extranjero':
                echo "E -".$reg->num_documento;
                    break;
                case $tip='Juridico':
                echo "J -".$reg->num_documento;
                    break;
            }        ?></td>
    </tr>
    <tr>
        <td><strong>Comprobante:</strong> <?php echo $reg->serie_comprobante." - ".$reg->num_comprobante ; ?></td>
    </tr>    
</table>

<!-- Mostramos los detalles de la venta en el documento HTML -->
<table border="0" align="center" width="300px">
    <tr>
        <td><strong>CANT.</strong></td>
        <td><strong>DESCRIPCIÓN</strong></td>
        <td align="right"><strong>MONTO</strong></td>
    </tr>
    <tr>
      <td colspan="3">==========================================</td>
    </tr>
    <?php
    $rsptad = $venta->DetalleFacturaVenta($_GET["id"]);
    $cantidad=0;
    while ($regd = $rsptad->fetch_object()) {
        echo "<tr>";
        echo "<td>".$regd->cantidad."</td>";
        echo "<td>".$regd->articulo;
        echo "<td align='right'>Bs ".$regd->subtotal."</td>";   
        echo "</tr>";
        
        $cantidad+=$regd->cantidad;
    }
    ?>
    <!-- Mostramos los totales de la venta en el documento HTML -->
    <tr>
    <td>&nbsp;</td>
    <td align="right"><b>TOTAL:</b></td>
    <td align="right"><b>Bs  <?php echo $reg->total_venta;  ?></b></td>
    </tr>
    <tr>
      <td colspan="3">Nº de artículos: <?php echo $cantidad; ?></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>      
    <tr>
      <td colspan="3" align="center">¡Gracias por su compra!</td>
    </tr>
    
</table>
<br>
</div>
<p>&nbsp;</p>

</body>
</html>
<?php 
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>