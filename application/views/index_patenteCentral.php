<?php
if (!(strtolower($this->session->userdata('usuario_tipo')) == 'patente_central')) {
header('Location: ' . site_url('login'));
} else {
require('cabecera.php');
if (empty($output)) {
?>

<a class="dashboard_button button2" href="<?php echo site_url('patente_central/concesion'); ?>">
    <span class="dashboard_button_heading">Patentes</span>
    <span>Pago de Patentes Mineras</span>
</a><!--end dashboard_button-->

<a class="dashboard_button button10" href="<?php echo site_url('patente_central/usuario/edit/'.$this->session->userdata('id_usuario')); ?>">
    <span class="dashboard_button_heading two_lines">Usuario</span>
    <span>Cambiar contrase&ntilde;a</span>
</a><!--end dashboard_button-->

<!--<a class="dashboard_button button9" href="#">
    <span class="dashboard_button_heading two_lines">Mensajes</span>
    <span>Enviar y Recibir mensajes</span>
</a>end dashboard_button

<a class="dashboard_button button12" href="#">
    <span class="dashboard_button_heading">Ayuda</span>
    <span>Manual de usuario para el manejo correcto del sistema.</span>
</a>end dashboard_button-->




<h2> Panel de informaci&oacute;n</h2>
<div class="content-box box-grey">
    <h4>Formularios de Patentes</h4>
    <p>Formularios Emitidos = 0</p>	
    <p>Fecha Limite para pagos = 31/01/2013</p>	
</div>

<div class="content-box box2">
    <h4>Patentes Mineras 2012</h4>
    <p>Patentes por PERTENENCIA Bs. 12,00</p>
    <p>Patentes por CUADRICULA Bs. 300,00</p>
</div>
<br /><br />
<?php } ?>

</div><!--end content_block-->


</div><!--end content-->

</div><!--end main-->

<div id="sidebar">
    <ul class="nav">
        <li><a class="headitem item1" href="#">Principal</a>
            <ul class="opened"><!-- ul items without this class get hiddden by jquery-->
                <li><a href="<?php echo site_url('patente_central'); ?>">Inicio</a></li>
                <li><a href="<?php echo site_url('patente_central/concesion'); ?>">Patentes de Concesiones Mineras</a></li>
                <li><a href="<?php echo site_url('patente_central/formularioPagoPatente'); ?>">Ingresar Form. Pago de patentes</a></li>
            </ul>
        </li>
        <li><a class="headitem item2" href="#">Reportes</a>
            <ul>
                <li><a href="<?php echo site_url('patente_central/reporte_diario'); ?>">Reporte Diario</a></li>
                <li><a href="<?php echo site_url('patente_central/reporte_general'); ?>">Reporte General</a></li>
                <li><a href="<?php echo site_url('patente_central/verificar_concesinesConPagosIncompletosDePatentes'); ?>">Concesiones con pagos incompletos</a></li>
                <li><a href="<?php echo site_url('patente_central/verificar_concesinesConPagosIncompletosDePatentesPertenencia'); ?>">Concesiones con pagos incompletos Pertenencia</a></li>
            </ul>
        </li>
        <li><a class="headitem item4" href="#">Cuenta de Usuario</a>
            <ul>
                <li><a href="<?php echo site_url('patente_central/usuario/edit/'.$this->session->userdata('id_usuario')); ?>">Cambiar contrase&ntilde;a</a></li>
<!--                <li><a href="<?php echo site_url('patente_central/mensaje'); ?>">Mensajes</a></li>-->
            </ul>
        </li>
    </ul><!--end subnav-->

<!--    <div class="flexy_datepicker"></div>-->

</div><!--end sidebar-->

</div><!--end bg_wrapper-->
<?php
require('pie.php');
}
?>