<form id="formResolucion" action="#">
    <p>
        <label for="name"> Concesion : <?php echo $concesion->nombre_concesion; ?> </label>
        <label for="name"> Nº Resolucion : <?php echo $concesion->numero_formulario; ?> </label>        
    </p>
    <p>
        <label for="name"> Nro Resoluci&oacute;n : </label>  <input id="nroResolucion" name="nroResolucion" class="input-small" type="text" value="RD-">
    </p>
    <p>    
        <label for="name"> Fecha Resolucion : </label>  <input id="fechaResolucion" name="fechaResolucion" class="input-small" type="text" value="">

    </p>
    <p>
        <input class="button" type="button" value="Guardar" id="btnResolucion">
    </p>
</form>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#btnResolucion").click(function(){
            if (confirm('Esta seguro de realizar el cambio?')){
                jQuery.post("", jQuery('#formResolucion :input'), function(datos){
                    jQuery("div#datos").html(datos);
                });
                jQuery( this ).dialog( "close" );
            }
        });
    });
</script>    
