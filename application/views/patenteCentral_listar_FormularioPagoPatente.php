<form name="formularioPagoPatente_1" action="<?php echo site_url('patente_central/guardar_formularioPagoPatente') ?>" method="POST">
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
