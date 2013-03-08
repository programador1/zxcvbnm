<div id="datos_adicionales" title="Ingrese los Datos Adicionales">
	<p class="msg_error">Ingrese todos los datos...</p>
	<div>
		<h1>B&uacute;squeda</h1>
        <div id="text"></div>
		<label for="buscar_personas">Buscar Nombre:</label>
            <select id="buscar_personas" name="buscar_personas">
            </select>
        <div id="testme"></div>
	</div>
	<form id="form_datos">
		<fieldset>
			<div id="datos">
			</div>
		</fieldset>
	</form>
</div>

<div id="tabla_listado" class="ui-widget">
<?php     echo $tablaPagos; // muestra los datos de la tabla?>  
</div>


<script type="text/javascript">
	jQuery(function() {
		var name = jQuery( "#name" ),
			email = jQuery( "#email" ),
			password = jQuery( "#password" ),
			allFields = jQuery( [] ).add( name ).add( email ).add( password ),
			tips = jQuery( ".msg_error" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}

		jQuery( "#datos_adicionales" ).dialog({
			autoOpen: false,
			width: 500,
			height: 600,
			modal: true,
			buttons: {
				"Imprimir Formulario": function() {
					jQuery( this ).dialog( "close" );
				},
				Cancelar: function() {
					jQuery( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		jQuery( ".emitir_formulario" )
			.button()
			.click(function() {
				jQuery( "#datos_adicionales" ).dialog( "open" );
			});
	});
    jQuery(document).ready(function(){
            jQuery("#buscar_personas").fcbkcomplete({
                json_url: '<?php echo site_url("patente_regional/patentes_persona");?>',
                addontab: true,                   
                maxitems: 1,
                maxshownitems: 10,
                delay: 1000,
                input_min_size: 0,
                width: 450,
                height: 10,
                cache: true,
                select_all_text: "Agregar Nuevo",
                complete_text: "Escriba el nombre o apellidos de la persona",
                onselect: function(){
            		var id_persona = jQuery('#buscar_personas').val();
            		//alert(id_persona+'recuperando datos de la persona');
            		jQuery.post('<?php echo site_url("patente_regional/patentes_encontrarPersona");?>'+ '/' +id_persona, {}, function(datos){
                		jQuery("div#datos").html(datos);
                	});
                }
            });
            jQuery("#datos").html("");
            jQuery("#nueva_persona").click(function(){
				alert('nueva persona');
				jQuery.post("datos.php", {}, function(datos){
            		jQuery("div#datos").html(datos);
            	});
            });
    });
</script>