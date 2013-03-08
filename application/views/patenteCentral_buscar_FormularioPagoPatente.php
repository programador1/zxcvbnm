
<form id="formularioPagoPatente" action="<?php echo site_url('patente_central/buscar_formularioPagoPatente') ?>" method="POST">
    <p>
        <label for="selectbox">Banco</label>
        <?php echo $combo_banco; ?>
    </p>

    <p>
        <label for="date">Fecha de Abono</label>
        <input id="fechaAbono" class="input-small garmaser" type="text" name="fechaAbono" value="<?php echo $fechaAbono; ?>">
    </p>
    <p>
        <label for="name">Nro Formulario de Pago de Patente </label>
        <input id="nroFormularioPagoPatente" class="input-small" type="text" name="nroFormularioPagoPatente" value="">
    </p>

    <p>
        <input class="button" type="submit" value="Aceptar" name="submit">
    </p>
</form>
<?php if (!empty($mensaje_error)) echo $mensaje_error;?>

<script type="text/javascript">
	jQuery("#formularioPagoPatente").validate({
		rules: {
			pagoPatente_banco: { required: true },
			fechaAbono: { required:true, date: true},
			nroFormularioPagoPatente: { required:true}
		},
		messages: {
			pagoPatente_banco: "El campo es obligatorio.",
			fechaAbono : "El campo es obligatorio y debe tener formato fecha.",
			nroFormularioPagoPatente : "El campo es obligatorio."
		}
	});
     
</script>


<div id="sergio" style="display:none">
    <p>sdfsadfsadfasdffffffff
    asdffffffffffffffffff
    dfgsdfgsdfgsdf</p>
    <p>sdfsadfsadfasdffffffff
    asdffffffffffffffffff
    dfgsdfgsdfgsdf</p>
    <p>sdfsadfsadfasdffffffff
    asdffffffffffffffffff
    dfgsdfgsdfgsdf</p>
    <p>sdfsadfsadfasdffffffff
    asdffffffffffffffffff
    dfgsdfgsdfgsdf</p>
    
    
    RAMBO
    
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery(".garmaser").datepicker(); //datepicker input field and box
    jQuery(".buttonx").click(function(){
        jQuery("#sergio").dialog({
            modal: true,
            src: '<?php site_url('patente_central/buscar_formularioPagoPatente') ?>'
        });
    });
}); 
</script>