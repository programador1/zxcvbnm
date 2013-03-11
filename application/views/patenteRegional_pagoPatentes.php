<div id="datos_adicionales" title="Ingrese los Datos Adicionales">
    <div class="msg_error">Ingrese todos los datos...</div>
    <div>
        <table>
            <tr>
                <td colspan="2"><h2>B&uacute;squeda</h2></td>
            </tr>
            <tr>
                <td>
                    <label for="buscar_personas">Buscar Nombre:</label>
                    <select id="buscar_personas" name="buscar_personas">
                    </select>
                </td>
                <td>
                    <button type="button" id="nueva_persona">Adicionar Nuevo</button>
                </td>
            </tr>
        </table>        
    </div>
    <form id="form_datos">
        <div id="datos">
        </div>
    </form>
</div>

<div id="tabla_listado" class="ui-widget">
    <?php echo $tablaPagos; // muestra los datos de la tabla?>  
</div>

<script type="text/javascript">
    jQuery(function() {
        var name = jQuery("#name"),
                email = jQuery("#email"),
                password = jQuery("#password"),
                allFields = jQuery([]).add(name).add(email).add(password),
                tips = jQuery(".msg_error");

        function updateTips(t) {
            tips
                    .text(t)
                    .addClass("ui-state-highlight");
            setTimeout(function() {
                tips.removeClass("ui-state-highlight", 1500);
            }, 500);
        }

        function checkLength(o, n, min, max) {
            if (o.val().length > max || o.val().length < min) {
                o.addClass("ui-state-error");
                updateTips("Length of " + n + " must be between " +
                        min + " and " + max + ".");
                return false;
            } else {
                return true;
            }
        }

        function checkRegexp(o, regexp, n) {
            if (!(regexp.test(o.val()))) {
                o.addClass("ui-state-error");
                updateTips(n);
                return false;
            } else {
                return true;
            }
        }

        jQuery("#datos_adicionales").dialog({
            autoOpen: false,
            width: 600,
            height: 600,
            modal: true,
            buttons: {
                "Imprimir Formulario": function() {
                    jQuery("#form_datos").attr("action", ".");
                    jQuery("#form_datos").submit();
                    jQuery(this).dialog("close");
                },
                Cancelar: function() {
                    jQuery(this).dialog("close");
                }
            },
            close: function() {
                jQuery("#form_datos :input").val("").removeClass("ui-state-error");
            }
        });

        jQuery(".emitir_formulario")
                .button()
                .click(function() {
            jQuery("#datos_adicionales").dialog("open");
        });
    });

    jQuery(document).ready(function() {
        jQuery("#buscar_personas").fcbkcomplete({
            json_url: '<?php echo site_url("patente_regional/patentes_persona"); ?>',
            addontab: true,
            maxitems: 1,
            maxshownitems: 30,
            delay: 1000,
            input_min_size: 0,
            width: 400,
            height: 15,
            cache: true,
            select_all_text: "",
            complete_text: "Escriba el nombre o apellidos de la persona",
            onremove: function() {
                jQuery("div#datos").html("");
                jQuery("input.maininput").removeAttr("size");
                jQuery("input.maininput").removeAttr("disabled");
                jQuery("#nueva_persona").show();
            },
            onselect: function() {
                var id_persona = jQuery('#buscar_personas').val();
                jQuery("#nueva_persona").hide();
                jQuery("input.maininput").attr("size", "1");
                jQuery("input.maininput").attr("disabled", "disabled");
                jQuery.post('<?php echo site_url("patente_regional/patentes_encontrarPersona"); ?>' + '/' + id_persona, {}, function(datos) {
                    jQuery("div#datos").html(datos);
                });
            }
        });
        jQuery("#datos").html("");
        jQuery("#nueva_persona")
                .button()
                .click(function() {
            jQuery.post('<?php echo site_url("patente_regional/patentes_encontrarPersona"); ?>', {}, function(datos) {
                jQuery("div#datos").html(datos);
            });
        });
    });
</script>