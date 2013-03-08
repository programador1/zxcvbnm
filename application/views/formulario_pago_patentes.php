<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <?php if (!empty($codigoBarras)) { ?>
            <script type="text/javascript" src="<?php echo base_url() ?>estilo/js/jquery.js"></script>
            <script type="text/javascript"> 
                var PAGINA = "http://www.google.com/";
                function redirigir() { 
                    document.location=PAGINA; 
                    alert ('se esta redirigiendo la pagina a: ' + PAGINA);
                }
                function imprSelec(div) {
                    var formulario=document.getElementById(div);
                    var ventana=window.open(' ','Vista Previa');
                    ventana.document.write(formulario.innerHTML);
                    ventana.document.close();
                                    
                    ventana.print();
                    ventana.close();
                                    
                }
                function imprimirPagina() {
                    if (confirm("Realmente desea imprimir este Formulario?")) {
                        imprSelec('Imprimir');
                                            
                        //redirigir();
                    }
                    window.history.back();
                }
            </script>
            <script type="text/javascript" src="<?php echo base_url() ?>estilo/js/jquery-barcode-2.0.2.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function(){
                    var valor = '<?php echo $codigoBarras; ?>';
                    var tipo = 'code128';
                    var opciones = {
                        output:       'css',
                        bgColor:      '#fff',
                        color:        '#000',
                        barWidth:     '1',
                        barHeight:    '50',
                        moduleSize:   '5',
                        posX:         '10',
                        posY:         '20',
                        addQuietZone: '1'
                    };
                    $("#codigo_barras").html("").show().barcode(valor, tipo, opciones);
                    imprimirPagina();
                });
            </script>


        <?php } ?>

    </head>
    <body>
        <br /><br />

        <div id="Imprimir" >
            <div class="divImprimir">
                <style type='text/css'>
                    body table tr td{ font-family: Arial;
                                      font-size: 11px;
                    }
                    .titulo{	
                        font-size: 14px;
                        font-weight: bold;
                    }
                    table td.subtitulo{	
                        font-size: 12px;
                        font-weight: bold;
                        background-color: #CCCCCC;
                    }
                    .txtNegrita{    font-weight: bold;
                                    text-align: right;
                    }
                    .tblEncabezado{ font-weight: bold;
                                    text-align: center;                    
                    }
                    .tblDatos{     text-align: center;                    
                    }
                    h1{ font-size: 14px; }
                    h2{ font-size: 14px; }
                    #Imprimir{ 
                        position:relative;
                        left: 25%;

                        width: 700px; 
                        padding: 5px;
                    }
                    #top table{
                        margin-bottom: 5px;
                    }
                    .divImprimir{                        
                        width: 700px; 
                        padding: 5px;
                        padding-top: 60px 
                    }
                </style>
                
                <table width="100%" border="0">
                    <tr height="30px">	<td class="subtitulo" colspan="4">DATOS DEL CONCESIONARIO: </td></tr>
                    <tr><td class="txtNegrita" width="100px"> Nombre :</td>		<td colspan="3"> <?php echo $nombreConcesionario; ?></td></tr>
                    <tr><td class="txtNegrita"> Nro Nit :</td>	 	<td><?php //echo $nit; ?> </td>
                        <td class="txtNegrita"> Tel&eacute;fono :</td>		<td><?php //echo $telefono; ?>	</td>
                    </tr>
                    <tr height="30px">	<td class="subtitulo" colspan="4">DATOS DE LA CONCESI&Oacute;N MINERA </td>	</tr>
                    <tr height="30px"><td class="txtNegrita" width="100px"> Nombre : </td> 	<td colspan="3"><?php echo $nombreConcesion; ?> </td>	</tr>
                    <tr><td class="txtNegrita"> Nro Inscripci&oacute;n : </td> 	<td><?php echo $numeroInscripcion; ?></td>
                        <td class="txtNegrita"> Departamento : </td>			<td><?php echo $departamento; ?></td></td>
                    </tr>
                    <tr><td class="txtNegrita" width="130px"> Nro Padr&oacute;n Nacional : </td> 	<td><?php echo $padronNacional; ?>
                        <td class="txtNegrita"> Provincia : </td>                    <td><?php echo $provincia; ?></td>
                    </tr>
                    <tr><td class="txtNegrita"> Resoluci&oacute;n : </td> <td><?php echo $resolucion; ?></td>
                        <td class="txtNegrita"> Cant&oacute;n : </td>			<td><?php echo $canton2; ?></td>
                    </tr>
                                               
                    <tr>  <td class="txtNegrita"> C&oacute;digo de Municipio : </td> 	<td><?php echo $codigo_municipio; ?></td>
                        <td class="txtNegrita"> Municipio : </td>			<td><?php echo $canton; ?></td>                  
                    </tr>
<!--                    <tr>    <td class="txtNegrita"> Minerales explotados : </td> 	<td colspan="3"> PLATA, PLOMO, ZING <br />(La explotacion de minerales definida se considera una declaracion jurada) </td> </tr>-->
                </table>
                <table width="100%" border="0">
                    <tr height="30px">	<td class="subtitulo" colspan="4" >DATOS DE PAGO DE LA PATENTE MINERA: <?php if ($progresivo) echo ' <b>(PAGO PROGRESIVO)</b>'; ?></td>	</tr>
                    <tr class="tblEncabezado"> 
                        <td> Pago de la Gesti&oacute;n </td>		
                        <td> <?php echo ucwords($tipoConcesion) . 'S asignadas'; ?></td>
                        <td> Importe por <?php echo $tipoConcesion; ?> </td>
                        <td> Total a cancelar </td>	
                    </tr>
                    <tr class="tblDatos">  
                        <td> <?php echo $gestion; ?> </td>							
                        <td> <?php echo $cantidadAsignada . ' [' . $unidad . ']' ?> </td>								
                        <td> <?php echo $importe ?> Bs.</td>										
                        <td> <?php echo $importeTotal . ' Bs.'; ?> </td>                                
                    <tr>                                               
                </table>

                <div id="codigo_barras">  
                </div>
<!--                <div style="font-family: Arial; font-size: 11px;"><br /><br />
                ROGER MAMANI NINA<br />
                CI: 40158965 LP
                </div>-->
            </div>  
        </div>
        <br /><br />
    </body>
</html>