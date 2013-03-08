<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html style="background-color: #ffffff;">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />

    </head>
    <body>
        <a id="volver" href="#" title="Volver a generar nuevo reporte"> <img src="<?php echo base_url() ?>estilo/images/boton_volver.png" width="119" height="41" alt="Volver"/> </a>
        <a id="simplePrint" href="#" title="Imprimir Reporte"> <img src="<?php echo base_url() ?>estilo/images/boton_imprimir.png" width="129" height="41" alt="Imprimir"/> </a>

        <hr />
        <div id="toPrint"  style="background-color:#ffffff; width: 100%; height: 100%;">
            <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/style_reporte.css" type="text/css" />
            <div id="imprimirReporte">
                
                <div id="cabeceraReporte">
                    <img src="<?php echo base_url('estilo/images/logo_formulario.png'); ?>" border="0" align="LEFT" style="padding-left:5px;"/>
                    <div>SERVICIO NACIONAL DE GEOLOGIA Y TECNICO DE MINAS <br /> <span>DIRECCION TECNICA DE MINAS Y SERVICIOS</span> </div>
                    <span> <?php echo $tipoReporte; ?></span>
                    <br />
                    SIACOMBO - SISTEMA DE ADMINISTRACION DE CONCESIONES MINERAS DE BOLIVIA
                </div>
                <div id="cuerpoReporte">
                    <?php
                    echo '<div style="text-align:right;">REP' . date('ymd') . '</div>';
                    echo alinear($tituloReporte,'centro','',20);

                    echo $reporte;
                    
                    ?>
                </div>
                <div id="pieReporte">
                </div>

            </div>
        </div>

    </body>
</html>
