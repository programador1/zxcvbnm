<table>
<tr>
<td>
	<div id="datos_personales">
		<h3>ADICI&Oacute;N / MODIFICACI&Oacute;N</h3>
		<label for="numero_identidad">Documento de Identidad:</label>
		<select name="documento_identidad" id="documento_identidad" style="display: inline;">
			<option value="">-Tipo-</option>
			<option value="CI">C.I.</option>
			<option value="PASAPORTE">Pasaporte</option>
			<option value="CI EXTRANJERO">C.I. Extranjero</option>
			<option value="RUN">R.U.N.</option>
		</select>
		<input type="text" name="numero_identidad" id="numero_identidad" class="text ui-widget-content ui-corner-all" style="display: inline; width: auto;" size="8" value="<?php if(!empty($persona['numero_identidad'])){echo $persona['numero_identidad'];} ?>" />
		<select name="lugar_expedido" id="lugar_expedido" style="display: inline;">
			<option value="">-Exp-</option>
			<option value="LP">LP</option>
			<option value="OR">OR</option>
			<option value="PT">PT</option>
			<option value="CB">CB</option>
			<option value="CH">CH</option>
			<option value="TJ">TJ</option>
			<option value="PA">PA</option>
			<option value="BN">BN</option>
			<option value="SC">SC</option>
		</select>
		<label for="nombre_persona">Nombre(s):</label>
		<input type="text" name="nombre_persona" id="nombre_persona" value="<?php if(!empty($persona['numero_identidad'])){echo $persona['nombre_persona'];} ?>" class="text ui-widget-content ui-corner-all" />
		<label for="paterno_persona">Apellido Paterno:</label>
		<input type="text" name="paterno_persona" id="paterno_persona" value="<?php if(!empty($persona['numero_identidad'])){echo $persona['paterno_persona'];} ?>" class="text ui-widget-content ui-corner-all" />
		<label for="materno_persona">Apellido Materno:</label>
		<input type="text" name="materno_persona" id="materno_persona" value="<?php if(!empty($persona['numero_identidad'])){echo $persona['materno_persona'];} ?>" class="text ui-widget-content ui-corner-all" />
		<label for="apellido_casada">Apellido de Casada:</label>
		<input type="text" name="apellido_casada" id="apellido_casada" value="<?php if(!empty($persona['numero_identidad'])){echo $persona['apellido_casada'];} ?>" class="text ui-widget-content ui-corner-all" />
		<label for="telefono">Tel&eacute;fono Personal:</label>
		<input type="text" name="telefono" id="telefono" value="<?php if(!empty($persona['telefono'])){echo $persona['telefono'];} ?>" class="text ui-widget-content ui-corner-all" />
	</div>
</td>
<td>
	<div id="datos_declaracion">
		<label for="numero_identidad">Tipo de Persona:</label>
		<select name="tipo_persona" id="tipo_persona">
			<option value="">--</option>
			<option value="TITULAR">Concesionario Titular</option>
			<option value="REPRESENTANTE LEGAL">Representante Legal</option>
			<option value="PERSONA NATURAL">Persona Natural</option>
		</select>
		<label for="nit">N.I.T.:</label>
		<input type="text" name="nit" id="nit" value="" class="text ui-widget-content ui-corner-all" />
		<label for="telefono">Tel&eacute;fono de Concesi&oacute;n:</label>
		<input type="text" name="telefono" id="telefono" value="" class="text ui-widget-content ui-corner-all" />
		<label for="minerales">Minerales Explotados:</label>
            <select id="minerales" name="minerales">
            </select>
	</div>
</td>
</tr>
</table>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#minerales").fcbkcomplete({
	    json_url: '<?php echo site_url("patente_regional/patentes_mineral");?>',
	    addontab: true,                   
	    maxitems: 10,
	    maxshownitems: 10,
	    delay: 1000,
	    input_min_size: 0,
	    width: 150,
	    height: 10,
	    cache: true,
	    select_all_text: "",
	    complete_text: "Seleccione los minerales explotados"
	});
});
</script>