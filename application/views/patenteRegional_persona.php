<?php
if (!empty($persona)) {
    $titulo = "MODIFICACION - Modifique los datos necesarios";
} else {
    $persona = array();
    $titulo = "ADICION - Ingrese los datos correctos";
}
?>
<table>
    <tr>
        <td colspan="2">
            <h3><?php echo $titulo; ?></h3>
        </td>
    </tr>
    <tr>
        <td>
            <div id="datos_personales">
                <label for="numero_identidad">Documento de Identidad:</label>
                <select name="documento_identidad" id="documento_identidad" style="display: inline;">
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
                </select>
                <label for="nombre_persona">Nombre(s):</label>
                <input type="text" name="nombre_persona" id="nombre_persona" value="<?php
                if (!empty($persona['nombre_persona'])) {
                    echo $persona['nombre_persona'];
                }
                ?>" class="text ui-widget-content ui-corner-all" />
                <label for="paterno_persona">Apellido Paterno:</label>
                <input type="text" name="paterno_persona" id="paterno_persona" value="<?php
                if (!empty($persona['paterno_persona'])) {
                    echo $persona['paterno_persona'];
                }
                ?>" class="text ui-widget-content ui-corner-all" />
                <label for="materno_persona">Apellido Materno:</label>
                <input type="text" name="materno_persona" id="materno_persona" value="<?php
                if (!empty($persona['materno_persona'])) {
                    echo $persona['materno_persona'];
                }
                ?>" class="text ui-widget-content ui-corner-all" />
                <label for="apellido_casada">Apellido de Casada:</label>
                <input type="text" name="apellido_casada" id="apellido_casada" value="<?php
                if (!empty($persona['apellido_casada'])) {
                    echo $persona['apellido_casada'];
                }
                ?>" class="text ui-widget-content ui-corner-all" />
                <label for="telefono_persona">Tel&eacute;fono Personal:</label>
                <input type="text" name="telefono_persona" id="telefono_persona" value="<?php
                if (!empty($persona['telefono'])) {
                    echo $persona['telefono'];
                }
                ?>" class="text ui-widget-content ui-corner-all" />
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
    jQuery(document).ready(function() {
        jQuery("#minerales").fcbkcomplete({
            json_url: '<?php echo site_url("patente_regional/patentes_mineral"); ?>',
            addontab: true,
            maxitems: 10,
            maxshownitems: 6,
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