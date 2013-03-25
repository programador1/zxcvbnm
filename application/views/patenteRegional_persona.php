<?php
if (!empty($persona)) {
    $titulo = "MODIFICACION - Modifique los datos necesarios";
} else {
    $persona = array();
    $titulo = "ADICION - Ingrese los datos correctos";
}
?>
<input type="hidden" name="id_persona" id="id_persona" value="<?php if (!empty($persona['id_persona'])) echo $persona['id_persona'];?>" class="text ui-widget-content ui-corner-all" />
<h2 style="padding-top: 10px;"><?php echo $titulo; ?></h2>
<table style="width: 100%;">
    <tr>
        <th> <center> Datos de solicitante </center></th>
		<th> <center> Datos de la concesi&oacute;n </center></th>
    </tr>
    <tr>
        <td>
            <div id="datos_personales">
                <table id="formatearTabla">
                    <tr>
                        <td colspan="2">
                            <div class="msg_info">Los campos con (*) son obligatorios</div>
                            <label for="numero_identidad">(*) Documento de Identidad:</label>
                        </td>
                        <td class="status"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><select name="documento_identidad" id="documento_identidad" style="display: inline;">
                                <option value="" <?php
                                if (!empty($persona['documento_identidad'])) {
                                    if ($persona['documento_identidad'] == '') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>-Tipo-</option>
                                <option value="CI" <?php
                                if (!empty($persona['documento_identidad'])) {
                                    if ($persona['documento_identidad'] == 'CI') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>C.I.</option>
                                <option value="PASAPORTE" <?php
                                if (!empty($persona['documento_identidad'])) {
                                    if ($persona['documento_identidad'] == 'PASAPORTE') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>Pasaporte</option>
                                <option value="CI EXTRANJERO" <?php
                                if (!empty($persona['documento_identidad'])) {
                                    if ($persona['documento_identidad'] == 'CI EXTRANJERO') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>C.I. Extranjero</option>
                                <option value="RUN" <?php
                                if (!empty($persona['documento_identidad'])) {
                                    if ($persona['documento_identidad'] == 'RUN') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>R.U.N.</option>
                            </select>
                            <input type="text" name="numero_identidad" id="numero_identidad" class="text ui-widget-content ui-corner-all" style="display: inline; width: auto;" size="12" maxlength="12" value="<?php
                            if (!empty($persona['numero_identidad'])) {
                                echo $persona['numero_identidad'];
                            }
                            ?>" />
                            <select name="lugar_expedido" id="lugar_expedido" style="display: inline;">
                                <option value="" <?php
                                if (!empty($persona['lugar_expedido'])) {
                                    if ($persona['lugar_expedido'] == '') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>-Exp-</option>
                                <option value="LP" <?php
                                if (!empty($persona['lugar_expedido'])) {
                                    if ($persona['lugar_expedido'] == 'LP') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>LP</option>
                                <option value="OR" <?php
                                if (!empty($persona['lugar_expedido'])) {
                                    if ($persona['lugar_expedido'] == 'OR') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>OR</option>
                                <option value="PT" <?php
                                if (!empty($persona['lugar_expedido'])) {
                                    if ($persona['lugar_expedido'] == 'PT') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>PT</option>
                                <option value="CB" <?php
                                if (!empty($persona['lugar_expedido'])) {
                                    if ($persona['lugar_expedido'] == 'CBBA') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>CB</option>
                                <option value="CH" <?php
                                if (!empty($persona['lugar_expedido'])) {
                                    if ($persona['lugar_expedido'] == 'CH') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>CH</option>
                                <option value="TJ" <?php
                                if (!empty($persona['lugar_expedido'])) {
                                    if ($persona['lugar_expedido'] == 'TJ') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>TJ</option>
                                <option value="PA" <?php
                                if (!empty($persona['lugar_expedido'])) {
                                    if ($persona['lugar_expedido'] == 'PA') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>PA</option>
                                <option value="BN" <?php
                                if (!empty($persona['lugar_expedido'])) {
                                    if ($persona['lugar_expedido'] == 'BN') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>BN</option>
                                <option value="SC" <?php
                                if (!empty($persona['lugar_expedido'])) {
                                    if ($persona['lugar_expedido'] == 'SC') {
                                        echo 'selected="selected"';
                                    }
                                }
                                ?>>SC</option>
                            </select></td>
                        <td class="status"></td>
                    </tr>
                    <tr>
                        <td><label for="nombre_persona">(*) Nombre(s):</label></td>
                        <td><input type="text" name="nombre_persona" id="nombre_persona" value="<?php
                            if (!empty($persona['nombre_persona'])) {
                                echo $persona['nombre_persona'];
                            }
                            ?>" class="text ui-widget-content ui-corner-all" /></td>
                        <td class="status"></td>
                    </tr>
                    <tr>
                        <td><label for="paterno_persona">Apellido Paterno:</label></td>
                        <td><input type="text" name="paterno_persona" id="paterno_persona" value="<?php
                            if (!empty($persona['paterno_persona'])) {
                                echo $persona['paterno_persona'];
                            }
                            ?>" class="text ui-widget-content ui-corner-all" /></td>
                        <td class="status"></td>
                    </tr>
                    <tr>
                        <td><label for="materno_persona">Apellido Materno:</label></td>
                        <td><input type="text" name="materno_persona" id="materno_persona" value="<?php
                            if (!empty($persona['materno_persona'])) {
                                echo $persona['materno_persona'];
                            }
                            ?>" class="text ui-widget-content ui-corner-all" /></td>
                        <td class="status"></td>
                    </tr>
                    <tr>
                        <td><label for="apellido_casada">Apellido de Casada:</label></td>
                        <td><input type="text" name="apellido_casada" id="apellido_casada" value="<?php
                            if (!empty($persona['apellido_casada'])) {
                                echo $persona['apellido_casada'];
                            }
                            ?>" class="text ui-widget-content ui-corner-all" /></td>
                        <td class="status"></td>
                    </tr>
                    <tr>
                        <td><label for="telefono_persona">(*) Tel&eacute;fono Personal:</label></td>
                        <td><input type="text" name="telefono_persona" id="telefono_persona" value="<?php
                            if (!empty($persona['telefono'])) {
                                echo $persona['telefono'];
                            }
                            ?>" class="text ui-widget-content ui-corner-all" /></td>
                        <td class="status"></td>
                    </tr>
                    <tr>
                        <td><label for="tipo_persona">(*) Tipo de Persona:</label></td>
                        <td><select name="tipo_persona" id="tipo_persona">
                                <option value="">-Seleccione-</option>
                                <option value="TITULAR">Concesionario Titular</option>
                                <option value="REPRESENTANTE LEGAL">Representante Legal</option>
                                <option value="PERSONA NATURAL">Persona Natural</option>
                            </select></td>
                        <td class="status"></td>
                    </tr>
                </table>
            </div>
        </td>
        <td>
            <div id="datos_declaracion">
                <table id="formatearTabla">
                    <tr>
                        <td>
                            <label for="nit">N.I.T.:</label>
                            <input type="text" name="nit" id="nit" value="<?php
                            if (!empty($nit)) {
                                echo $nit;
                            }
                            ?>" class="text ui-widget-content ui-corner-all" />
                        </td>
                        <td class="status"></td>
                    </tr>
                    <tr>
                        <td>
                            <label for="telefono">Tel&eacute;fono de Concesi&oacute;n:</label>
                            <input type="text" name="telefono" id="telefono" value="<?php
                            if (!empty($telefono)) {
                                echo $telefono;
                            }
                            ?>" class="text ui-widget-content ui-corner-all" />
                        </td>
                        <td class="status"></td>
                    </tr>
                    <tr>
                        <td>
                            <label for="minerales">Minerales Explotados:</label>
                            <select id="minerales" name="minerales">
                            </select>
                        </td>
                        <td class="status"></td>
                    </tr>
                </table>
                <br /><br /><br />
                <button id="imprime_formulario" type="submit" class="button">Imprimir Formulario</button>
            </div>
        </td>
    </tr>
</table>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#minerales").fcbkcomplete({
            json_url: '<?php echo site_url("patente_regional/patentes_mineral"); ?>',
            addontab: true,
            maxitems: 10,
            maxshownitems: 6,
            input_min_size: 0,
            width: 150,
            height: 10,
            cache: true,
            select_all_text: "",
            complete_text: "Seleccione los minerales explotados",
            onremove: function() {
                jQuery("input.maininput").removeAttr("size");
            },
            onselect: function() {
                jQuery("input.maininput").attr("size", "1");
            }
        });
        jQuery.validator.addMethod("solotexto", function(value, element) {
            return this.optional(element) || /^[a-zA-ZáéíóúAÉÍÓÚÑñ ]+$/.test(value);
        }, "Solo Texto.");
        jQuery.validator.addMethod("telefonos", function(value, element) {
            return this.optional(element) || /^[0-9,]+$/.test(value);
        }, "Telefonos.");
        jQuery("form#form_datos").validate({
            rules: {
                documento_identidad: {required: true},
                numero_identidad: {required: true, minlength: 6, maxlength: 15, digits: true},
                lugar_expedido: {required: true},
                nombre_persona: {required: true, solotexto: true},
                paterno_persona: {solotexto: true},
                materno_persona: {solotexto: true},
                apellido_casada: {solotexto: true},
                telefono_persona: {required: true, minlength: 5, telefonos: true},
                tipo_persona: {required: true},
                nit: {minlength: 8, maxlength: 15, digits: true},
                telefono: {minlength: 5, telefonos: true}
            },
            messages: {
                documento_identidad: {required: "Seleccione Documento"},
                numero_identidad: {required: "Escriba el CI", minlength: "Demasiado Corto", maxlength: "Demasiado Largo", digits: "Solo numeros"},
                lugar_expedido: {required: "Seleccione EXP"},
                nombre_persona: {required: "Nombre obligatorio", solotexto:"Solo texto"},
                paterno_persona: {solotexto:"Solo texto"},
                materno_persona: {solotexto:"Solo texto"},
                apellido_casada: {solotexto:"Solo texto"},
                telefono_persona: {required: "Telefono necesario", minlength: "TELEFONO muy corto", telefonos: "Ingrese telefono"},
                tipo_persona: {required: "Que representacion tiene"},
                nit: {minlength: "NIT muy corto", maxlength: "NIT muy largo", digits: "Solo numeros"},
                telefono: {minlength: "TELEFONO muy corto", telefonos: "no valido"}
            },
            errorPlacement: function(error, element) {
                if (element.is(":radio"))
                    error.appendTo(element.parent().next().next());
                else if (element.is(":checkbox"))
                    error.appendTo(element.next());
                else
                    error.appendTo(element.parent().next());
            },
            submitHandler: function() {
                if(jQuery("#minerales").val()){
                    if(confirm("Esta seguro de Imprimir el Formulario?")){
                        this.submit();
                    }
                }else{
                    alert("Indique los minerales que se han explotado...");
                }
            },
            success: function(label) {
                label.html("&nbsp;").addClass("checked");
            },
            highlight: function(element, errorClass) {
                jQuery(element).parent().next().find("." + errorClass).removeClass("checked");
            }
        });
    });
</script>