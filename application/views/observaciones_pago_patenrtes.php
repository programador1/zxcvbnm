<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
<style type='text/css'>
	body{	font-family: Arial;
		font-size: 12px;
	}
	.subtitulo{	
		font-size: 14px;
                font-weight: bold;
                background-color: #CCCCCC;
	}
        .txtNegrita{    font-weight: bold;
                        text-align: right;
        }
        .tblEncabezado{ font-weight: bold;
                        text-align: center;                    
        }
        .tblDatos{     text-align: center;                    
        }
        h1{ font-size: 16px; }
        h2{ font-size: 14px; }
</style>
</head>
<body>
<?php 
if($datos->nombre_empresa==NULL OR $datos->nombre_empresa=='')
        $nombre = $datos->nombre_persona.' '.$datos->paterno_persona.' '.$datos->materno_persona;
else
        $nombre = $datos->nombre_empresa;
?>

<table border='1' width="600px" align="center">
	<tr>	<td>
			<table width="100%">
					<tr> 
                                            <td> <img src="<?php echo base_url('images/logo_formulario.jpg');?>" width="89" height="92" alt="logo sergeotecmin"/>
                                            </td>
							<td align="center"> 	SERVICIO NACIONAL DE GEOLOGIA Y TECNICO DE MINAS
									<br /> <b>"SERGEOTECMIN"</b>
									<h1>FORMULARIO DE PAGO DE PATENTES MINERAS</h1>
							
                                                        </td>
							<td align="center"> <h2> &#8470; 192105 </h2>                                                        
                                                         <?php echo date('d/m/Y');?>
                                                        <br /> Fecha emision
                                                        </td>
					</tr>
					
			</table>
	
	
			</td>
	</tr>
	
	<tr>	<td>	
                        <table width="100%">
                                        <tr>	<td class="subtitulo" colspan="4">DATOS DEL CONCESIONARIO: </td></tr>
                                        
                                        <tr><td class="txtNegrita" width="90px"> Nombre :</td>		<td colspan="3"> <?php echo $nombre; ?></td></tr>
                                        <tr><td class="txtNegrita"> Nro Nit :</td>	 	<td>4018645	</td>
                                            <td class="txtNegrita"> Telefono :</td>		<td>72088275	</td>
                                        </tr>
                          
                                        
                                        <tr>	<td class="subtitulo" colspan="4">DATOS DE LA CONCESION MINERA </td>	</tr>
                                        
                                        <tr height="30px"><td class="txtNegrita"> Nombre : </td> 	<td colspan="3"><?php echo $datos->nombre_concesion; ?> </td>	</tr>
                                        
                                        <tr><td class="txtNegrita"> &#8470; Inscripci&oacute;n : </td> 	<td><?php echo $datos->numero_formulario; ?></td>
                                            <td class="txtNegrita"> &#8470; Padr&oacute;n Nacional : </td> 	<td><?php echo $datos->padron_nacional; ?></td>
                                        </tr>
                                        <tr><td class="txtNegrita"> Departamento : </td>			<td><?php echo $datos->departamento; ?></td>
                                            <td class="txtNegrita"> Provincia : </td>                    <td><?php echo $datos->provincia; ?></td>
                                        </tr>
                                        <tr><td class="txtNegrita"> Canton : </td>			<td><?php echo $datos->canton; ?></td>
                                            <td class="txtNegrita"> C&oacute;digo de Municipio : </td> 	<td><?php echo $datos->codigo_municipio; ?></td>
                                        </tr>
                        </table>
                
                        <table width="100%">
                                       <tr>	<td class="subtitulo" colspan="4">DATOS DE PAGO DE LA PENTE MINERA: </td>	</tr>
                                        <tr class="tblEncabezado"> 
                                            <td> Pago de la Gestion </td>		
                                            <td> <?php echo ucwords($datos->tipo_concesion).'s asignadas';?></td>
                                            <td> Importe por <?php echo $datos->tipo_concesion ?> </td>
                                            <td> Total a cancelar </td>	
                                        </tr>
                                        <tr class="tblDatos">  
                                            <td> <?php echo date('Y'); ?> </td>							
                                            <td> <?php echo $datos->cantidad_asignada.' ['.$datos->unidad.']' ?> </td>								
                                            <td> 300 Bs</td>										
                                            <td> <?php echo (300*$datos->cantidad_asignada).' Bs.'; ?> </td>                                
                                        <tr>                                               
                        </table>
                </td>
	</tr>
</table>
</body>
</html>
