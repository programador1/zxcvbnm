<div id="resultadoDatos">
    <form id="formResolucion" action="#">
        <table id="formatearTabla" align="center" width="100%">
            <tr>
                <td align="right" width="130px"><label for="name"><b> N&deg; Inscripci&oacute;n : </b> </td><td><?php echo $concesion->numero_formulario; ?> </label> </td>
            </tr>
            <tr>
                <td align="right"><label for="name"><b> Concesion : </b></td><td><?php echo $concesion->nombre_concesion; ?> </label></td>
            </tr>
            <tr>
                <td align="right"><label for="name"><b> Concesionario : </b></td><td><?php echo $concesion->concesionario; ?> </label></td>
            </tr>
            <tr>
                <td align="right"><label for="name"><b> Cantidad Asignada : </b></td><td><?php echo $concesion->cantidad_asignada.' ['.$concesion->unidad.']'; ?> </label></td>
            </tr>
        </table>
        <br />
        <h2 align="center"> INGRESE LOS DATOS DE LA RESOLUCI&Oacute;N </h2>
        <table id="formatearTabla" align="center" width="100%">
            <tr><td align="center">
                    <label for="selectbox"> TIPO RESOLUCI&Oacute;N </label>
                    <select id="tipoResolucion" name="tipoResolucion">
                        <option value="RD-">Resoluci&oacute;n de Directorio</option>
                        <option value="RC-">Resoluci&oacute;n Constutiva</option>
                    </select>
                </td>
                <td align="center">
                    <label for="name"> N&deg; RESOLUCI&Oacute;N</label>
                    <input id="numeroResolucion" name="numeroResolucion" class="input-small" type="text" value="">
                </td>
                <td align="center">
                    <label for="name"> FECHA RESOLUCI&Oacute;N</label>  
                    <input id="fechaResolucion" name="fechaResolucion" class="input-small garmaser" type="text" value="">
                </td>
            </tr>
            <tr><td colspan="3" align="center">
                    <br /><br />
                    <input class="button" type="submit" value="Guardar Resoluci&oacute;n" id="btnResolucion">
                </td></tr>
        </table>
    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#__btnResolucion").click(function() {
            if (confirm('Esta seguro de realizar el cambio?')) {
                jQuery.post("<?php echo site_url('patente_regional/resolucion_InsertarDatos/' . $concesion->id_concesion_minera); ?>", jQuery('#formResolucion :input'), function(datos) {
                    jQuery("div#resultadoDatos").html(datos);
                });
                //jQuery(this).dialog("close");
            }
        });

        jQuery(".garmaser").datepicker({dateFormat: 'dd/mm/yy'}); //datepicker input field and box
        jQuery(".buttonx").click(function() {
            jQuery("#sergio").dialog({
                modal: true,
                src: '<?php site_url('patente_central/buscar_formularioPagoPatente') ?>'
            });
        });

        /*jQuery("#buttonRetornar").click(function(){
         jQuery("#divResolucion").dialog( "close" );
         jQuery("#crud_search").click();
         });*/
        jQuery.validator.addMethod(
                "fecha",
                function(value, element) {
                    // put your own logic here, this is just a (crappy) example
                    return value.match(/^\d\d?\/\d\d?\/\d\d\d\d$/);
                },
                "Please enter a date in the format dd/mm/yyyy."
                );
        jQuery("#formResolucion").validate({
            rules: {
                numeroResolucion: {required: true},
                fechaResolucion: {required: true, fecha: true}
            },
            messages: {
                numeroResolucion: "El campo es obligatorio.",
                fechaResolucion: "El campo es obligatorio y debe tener formato fecha."
            },
            submitHandler: function(form) {
                if (confirm('Esta seguro de realizar el cambio?')) {
                    jQuery.post("<?php echo site_url('patente_regional/resolucion_InsertarDatos/' . $concesion->id_concesion_minera); ?>", jQuery('#formResolucion :input'), function(datos) {
                        jQuery("div#resultadoDatos").html(datos);
                    });
                    //jQuery(this).dialog("close");
                }
            }


        });

    });

</script>    
