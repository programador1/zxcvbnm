<?php
if (!(strtolower($this->session->userdata('usuario_tipo'))=='patente_regional')){
header('Location: '.site_url('login'));
}else{
require('cabecera.php');
if (empty($output)) {
?>

<a class="dashboard_button button2" href="<?php echo site_url('patente_regional/patentes'); ?>">
    <span class="dashboard_button_heading">Patentes</span>
    <span>Concesiones Mineras</span>
</a><!--end dashboard_button-->

<a class="dashboard_button button10" href="<?php echo site_url('patente_regional/usuario/edit/'.$this->session->userdata('id_usuario')); ?>">
    <span class="dashboard_button_heading two_lines">Usuario</span>
    <span>Cambiar contrase&ntilde;a</span>
</a><!--end dashboard_button-->

<!--<a class="dashboard_button button9" href="<?php echo site_url('patente_regional/mensaje'); ?>">
    <span class="dashboard_button_heading two_lines">Mensajes</span>
    <span>Enviar y Recibir mensajes</span>
</a>end dashboard_button-->

<!--<a class="dashboard_button button12" href="#">
    <span class="dashboard_button_heading">Ayuda</span>
    <span>Manual de usuario para el manejo correcto del sistema.</span>
</a>end dashboard_button-->


<h2> Panel de informaci&oacute;n</h2>
<div class="content-box box-grey">    
    <h4>Patentes Mineras 2013 por CUADRICULA</h4>
    <p>Patentes por CUADRICULA SIMPLE 325,00 Bs.</p>
    <p>Patentes por PERTENENCIA PROGRESIVO 650,00 Bs.</p>
</div>

<div class="content-box box2">
    <h4>Patentes Mineras 2013 POR PERTENENCIA</h4>
    <p>Patentes por PERTENENCIA SIMPLE 13,00 Bs.</p>
    <p>Patentes por PERTENENCIA PROGRESIVO 26,00 Bs.</p>
    
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
                <li><a href="<?php echo site_url('patente_regional'); ?>">Inicio</a></li>
                <li><a href="<?php echo site_url('patente_regional/patentes'); ?>">Buscar Concesiones Mineras</a></li>
<!--                <li><a href="<?php echo site_url('patente_regional'); ?>">Ayuda</a></li>-->
            </ul>
        </li>

        <li><a class="headitem item4" href="#">Cuenta de Usuario</a>
            <ul>
                <li><a href="<?php echo site_url('patente_regional/usuario/edit/'.$this->session->userdata('id_usuario')); ?>">Cambiar contrase&ntilde;a</a></li>
<!--                <li><a href="<?php echo site_url('patente_regional/mensaje'); ?>">Mensajes</a></li>-->
            </ul>
        </li>
    </ul><!--end subnav-->

    <div class="flexy_datepicker"></div>

</div><!--end sidebar-->

</div><!--end bg_wrapper-->
<?php
require('pie.php');
}
?>