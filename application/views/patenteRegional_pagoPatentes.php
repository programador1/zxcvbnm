<div id="datos_adicionales" title="Emisi&oacute;n del Formulario de Pago de Patentes Mineras">
    <div class="msg_error">Debe asegurarse de seleccionar e ingresar los datos correctos...</div>
    <div>
        <table id="formatearTabla">
            <tr>
                <th colspan="2">B&uacute;squeda</th>
            </tr>
            <tr>
                <td>
                    <label for="buscar_personas">Buscar por C.I., Nombres o Apellidos del Concesionario:</label>
                    <select id="buscar_personas" name="buscar_personas">
                    </select>
                </td>
                <td>
                    <button type="button" id="nueva_persona">Adicionar Nuevo</button>
                </td>
            </tr>
        </table>        
    </div>
    <form id="form_datos" method="POST" action="<?php echo site_url("patente_regional/patentes_guardarDatos"); ?>">
        <input type='hidden' name='gestion' id='gestion' value=''>
        <input type='hidden' name='importe' id='importe' value=''>
        <div id="datos">
        </div>
    </form>
</div>

<div id="tabla_listado" class="ui-widget">
    <?php 
    $html = '<br /><b>Concesion : </b>'.$concesion->nombre_concesion;    
    $html.= '<br /><b>Concesionario : </b>'.$concesion->concesionario;
    $html.= '<br /><b>Nro Inscripci&oacute;n : </b>'.$concesion->numero_formulario;
    $html.= '<br /><b>Fecha Resoluci&oacute;n : </b>'.$fechaResolucion.'<br /><br />';
    echo $html;
    echo $tablaPagos; // muestra los datos de la tabla
    ?>    
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#datos_adicionales").dialog({
            autoOpen: false,
            width: 850,
            height: 600,
            
            modal: true,
            buttons: {
                "Cerrar Formulario": function() {
                    jQuery(this).dialog("close");
                }
            },
            close: function() {
                jQuery("a.closebutton").click();
                jQuery("div#datos").html("");
                jQuery("#form_datos :input").val("").removeClass("ui-state-error");
            }
        });
        jQuery(".emitir_formulario")
                .button()
                .click(function(e) {
                jQuery("#gestion").attr('value',e.target.id);
                jQuery("#importe").attr('value',e.target.name);
            jQuery("#datos_adicionales").dialog("open");
        });
        jQuery("#buscar_personas").fcbkcomplete({
            json_url: '<?php echo site_url("patente_regional/patentes_persona"); ?>',
            addontab: true,
            maxitems: 1,
            maxshownitems: 30,
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