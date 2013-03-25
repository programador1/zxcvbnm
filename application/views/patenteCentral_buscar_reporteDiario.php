<form id="formularioPagoPatente_1" action="<?php echo $url; ?>" method="POST">

    <p>
        <label for="date">Fecha Inicio</label>
        <input id="fechaInicio" class="input-small garmaser" type="text" name="fechaInicio" value="--/--/----">
    </p>
    
    <?php if(empty($reporte_diario)){?>
    <p>
        <label for="date">Fecha Final</label>
        <input id="fechaFinal" class="input-small garmaser" type="text" name="fechaFinal" value="--/--/----">
    </p>
    <?php }?>

    <p>
        <input class="button" type="submit" value="Generar Reporte" name="submit">
    </p>
</form>
<?php if (!empty($mensaje)) echo $mensaje;?>

<script type="text/javascript">
	jQuery("#formularioPagoPatente_1").validate({
		rules: {
			fechaInicio: { required: true, date:true },
			fechaFinal: { required:true, date:true }
		},
		messages: {
			fechaInicio: "El campo es obligatorio y debe tener formato fecha.",
			fechaFinal : "El campo es obligatorio y debe tener formato fecha."
                }
        });
</script>

<div id="sergio" style="display:none">

    
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery(".garmaser").datepicker({dateFormat: 'yy-mm-dd'}); //datepicker input field and box
    jQuery(".buttonx").click(function(){
        jQuery("#sergio").dialog({
            modal: true,
            src: '<?php site_url('patente_central/buscar_formularioPagoPatente') ?>'
        });
    });
}); 
</script>