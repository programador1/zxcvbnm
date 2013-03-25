<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <?php if (!empty($codigoBarras)) { ?>
            <script type="text/javascript" src="<?php echo base_url() ?>estilo/js/jquery.js"></script>
            <script type="text/javascript">
                var PAGINA = "<?php echo site_url('patente_regional/patentes_controlarPagoDePatentes/' . $this->session->userdata('id_concesion_minera')); ?>";
                function redirigir() {
                    document.location = PAGINA;
                    //alert ('se esta redirigiendo la pagina a: ' + PAGINA);
                }
                function imprSelec(div) {
                    var formulario = document.getElementById(div);
                    var ventana = window.open(' ', 'Vista Previa');
                    ventana.document.write(formulario.innerHTML);
                    ventana.document.close();

                    ventana.print();
                    ventana.close();

                }
                function imprimirPagina() {
                    //if (confirm("Realmente desea imprimir este Formulario?")) {
                    imprSelec('Imprimir');

                    redirigir();
                    //}
                    //window.history.back();
                }
            </script>
            <script type="text/javascript" src="<?php echo base_url() ?>estilo/js/jquery-barcode-2.0.2.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    var valor = '<?php echo $codigoBarras; ?>';
                    var tipo = 'code128';
                    var opciones = {
                        output: 'css',
                        bgColor: '#fff',
                        color: '#000',
                        barWidth: '1',
                        barHeight: '50',
                        moduleSize: '5',
                        posX: '10',
                        posY: '20',
                        addQuietZone: '1'
                    };
                    $(".codigo_barras").html("").show().barcode(valor, tipo, opciones);
                    imprimirPagina();
                });
            </script>


        <?php } ?>

    </head>
    <body>
        <?php
        $resolucionInscripcion='Resoluci&oacute;n';
        if($tipoConcesion=='PERTENENCIA') $resolucionInscripcion='Inscripci&oacute;n';
        $html1 = '<div style="height: 500px;">
    <table width="100%" border="0" style="padding-top: 100px;">
        <tr> <td align="center" class="subtitulo">';
        $html2 =
                <<<REPETIR
        </td> <td colspan="3"></td></tr>
        <tr> <td rowspan="3" align="center" width="100px"> <div class="codigo_barras"> </div> </td> 
        <td class="subtitulo" colspan="3">DATOS DEL CONCESIONARIO</td> </tr>
        <tr><td class="txtNegrita" width="100px"> Nombre :</td>		<td colspan="2"> {$nombreConcesionario}</td> </tr>
        <tr><td class="txtNegrita"> N&deg; NIT :</td>	 	<td> {$nit} </td>
            <td> <b> Tel&eacute;fono : </b>{$telefono} &nbsp;&nbsp;&nbsp;</td>
        </tr>
        </table>
        <table width="100%" border="0">
        <tr height="30px">	<td class="subtitulo" colspan="4">DATOS DE LA CONCESI&Oacute;N MINERA </td>	</tr>
        <tr height="30px"><td class="txtNegrita" width="100px"> Nombre : </td> 	<td colspan="3">{$nombreConcesion} </td>	</tr>
        <tr><td class="txtNegrita"> N&deg; Inscripci&oacute;n : </td> 	<td> {$numeroInscripcion} </td>
            <td class="txtNegrita"> Departamento : </td>			<td> {$departamento} </td></td>
        </tr>
        <tr><td class="txtNegrita" width="130px"> N&deg; Padr&oacute;n Nacional : </td> 	<td> {$padronNacional} 
            <td class="txtNegrita"> Provincia : </td>                    <td> {$provincia} </td>
        </tr>
        <tr><td class="txtNegrita"> {$resolucionInscripcion} : </td> <td> {$resolucion} </td>
            <td class="txtNegrita"> Municipio : </td>			<td> {$canton} </td>
        </tr>

        <tr>  <td class="txtNegrita"> C&oacute;digo de Municipio : </td> 	<td> {$codigo_municipio} </td>
            <td class="txtNegrita">  </td>			<td> </td>                  
        </tr>
        <tr>    <td class="txtNegrita"> Minerales Explotados : </td> <td colspan="3" style="border: 1px solid; padding-left: 5px;">  {$mineral}  <br />(La informaci&oacute;n proporcionada sobre la explotaci&oacute;n minera se considera como declaraci&oacute;n jurada) </td> </tr>
        <tr height="30px">	<td class="subtitulo" colspan="4" >DATOS DEL PAGO DE LA PATENTE MINERA <span class="textoNormal">(Formulario v&aacute;lido hasta el  {$fechaLimite} )</span></td>	</tr>
        <tr><td colspan="4">
        <table width="100%" id="montos">
        <tr class="tblEncabezado"> 
            <td> GESTI&Oacute;N </td>		
            <td> N&deg;  {$tipoConcesion}S</td>
            <td> PATENTE  {$importeGestion}    </td>
            <td> TIPO </td>	
            <td> IMPORTE </td>	
        </tr>
        <tr class="tblDatos">  
            <td>  {$gestion}  </td>
            <td>  {$cantidadAsignada } [{$unidad}] </td>								
            <td>  {$importe} Bs.</td>										
            <td>  {$tipoImporte} </td>
            <td>  {$importeTotal} Bs. </td>                                
        <tr>                                               
    </table>
        </td></tr>
    </table>
    
    <div class="textoNormal" style="padding-top:5px;"><b>Son:</b>  {$importeTotalLiteral}  </div>
</div>
<div class="textoNormal" style="font-size: 9px;">
    <table border="0" width="200px" >
        <tr> <td align="center">  {$solicitante}  </td> </tr>
    </table>
</div>
REPETIR;
        ?>


        <br /><br />

        <div id="Imprimir" >
            <div class="divImprimir">
                <style type='text/css'>
                    body table tr td{ font-family: Arial;
                                      font-size: 11px;
                    }
                    .textoNormal {
                        font-family: Arial;
                        font-size: 11px;
                        font-weight: normal;
                    }
                    .titulo{	
                        font-size: 14px;
                        font-weight: bold;
                    }
                    table td.subtitulo{	
                        font-size: 12px;
                        font-weight: bold;
                        background-color: #CCCCCC;
                        padding-left: 8px;
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
                        width: 745px; 
                        padding: 5px;                         
                    }
                    table#montos, table#montos tr, table#montos td{
                        border: none;
                        padding: 0px;
                        margin: 0px;
                        border-collapse: collapse;
                    }
                    table#montos td{
                        border: 1px solid;
                    }

                    .salto_pagina_despues{     
                        page-break-after:always;
                    }

                    #salto_pagina_anterior{
                        page-break-before:always;
                    }
                </style>
                                
                <div class = "salto_pagina_despues">
                    <?php echo $html1 . 'CLIENTE' . $html2; ?>
                </div>

                <div class = "salto_pagina_despues">
                    <?php echo $html1 . 'BANCO' . $html2; ?>
                </div>

                <div class = "salto_pagina_despues">
                    <?php echo $html1 . 'SERGEOTECMIN' . $html2; ?>
                </div>

                <div class = "salto_pagina_despues">
                    <?php echo $html1 . 'SERGEOTECMIN' . $html2; ?>
                </div>







            </div>  
        </div>
        <br /><br />
    </body>
</html>