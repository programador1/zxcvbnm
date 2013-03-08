<?php if (!empty($btnVolver)) { ?>
    <a id="volver" href="#" title="Volver"> <img src="<?php echo base_url() ?>estilo/images/boton_volver.png" width="119" height="41" alt="Volver"/> </a>
    <hr />
<?php } ?>


<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-Mostrar datos para CUADRICULAS //////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-Formatear fechas
if(!empty($fecha_resolucion)) $fecha_resolucion = date('d-m-Y', strtotime($fecha_resolucion));
if(!empty($fecha_inscripcion)) $fecha_inscripcion = date('d-m-Y', strtotime($fecha_inscripcion));
if(!empty($fecha_gaceta)) $fecha_gaceta = date('d-m-Y', strtotime($fecha_gaceta));
if(!empty($FECADJ)) $FECADJ = date('d-m-Y', strtotime($FECADJ));
if(!empty($FECTIT)) $FECTIT = date('d-m-Y', strtotime($FECTIT));

if ($tipo_concesion == 'CUADRICULA') {
?>
    <table cellspacing="0" align="center" border="0">
        <tr>
            <th colspan="4"> DATOS DE CONCESION  </th>
        </tr>
        <tr>
            <th class="specalt"> Nombre: </th> <td colspan="3"> <h3><?php echo $nombre_concesion; ?></h3> </th>            
        </tr>
        <tr>
            <th class="specalt"> Nro Inscripcion:</th> <td> <?php echo $numero_formulario; ?> </td>
            <th class="specalt"> Nro Padron Nacional: </th> <td> <?php echo $padron_nacional; ?> </td>
        </tr>    
        <tr>
            <th class="specalt"> Nro Resolucion: </th> <td> <?php echo $numero_resolucion; ?> </td>
            <th class="specalt"> Fecha REsolucion: </th> <td> <?php echo $fecha_resolucion; ?> </td>
        </tr>
        <tr>
            <th class="specalt"> Fecha Inscripcion: </th> <td> <?php echo $fecha_inscripcion; ?> </td>
            <th class="specalt"> Regional:</th> <td> <?php echo $regional; ?> </td>
        </tr>
        <tr>
            <th class="specalt"> Tipo: </th> <td> <?php echo $tipo_concesion; ?> </td>
            <th class="specalt"> Estado: </th> <td> <?php echo $estado_concesion; ?> </td>
        </tr>

        <tr>
            <th colspan="4"> DATOS DE CONCESIONARIO  </th>
        </tr>
        <tr>
            <th class="specalt"> Nombre: </th> <td colspan="3">
                <?php
                if (!empty($nombre_empresa))
                    echo $nombre_empresa;
                else
                    echo $nombre_persona . ' ' . $paterno_persona . ' ' . $materno_persona;
                ?> </td>            
        </tr>
        <tr>
            <th class="specalt"> Nro NIT: </th> <td> <?php echo $numeroNit = ''; ?> </td>  
            <th class="specalt"> Telefono: </th> <td> <?php echo $telefono = ''; ?> </td> 
        </tr>


        <tr>
            <th colspan="4"> DATOS DE UBICACION  </th>
        </tr>
        <tr>
            <th class="specalt"> Departamento: </th> <td> <?php echo $departamento; ?> </td>  
            <th class="specalt"> Provincia: </th> <td> <?php echo $provincia; ?> </td> 
        </tr>
        <tr>
            <th class="specalt"> Canton: </th> <td> <?php echo $canton; ?> </td>  
            <th class="specalt"> Codigo Municipio: </th> <td> <?php echo $codigo_municipio; ?> </td> 
        </tr>
        <tr>
            <th class="specalt"> Nro Hoja Cartografica: </th> <td> <?php echo $numero_hoja; ?> </td>  
            <th class="specalt"> Nombre Hoja Cartografica: </th> <td> <?php echo $nombre_hoja; ?> </td> 
        </tr>

        <tr>
            <th colspan="4"> DATOS DE SOLICITUD  </th>
        </tr>
        <tr>
            <th class="specalt"> Asignadas: </th> <td> <?php echo $cantidad_asignada . ' [' . $unidad . ']'; ?> </td> 
            <th class="specalt"> Solicitadas: </th> <td> <?php echo $cantidad_solicitada . ' [' . $unidad . ']'; ?> </td>          
        </tr>
        <tr>
            <th class="specalt"> En area franca: </th> <td> <?php echo $cuadriculas_franca; ?> </td> 
            <th class="specalt"> Parcialmente sobrepuestas: </th> <td> <?php echo $cuadriculas_parciales; ?> </td>          
        </tr>
        <tr>
            <th class="specalt"> Plano definitivo: </th> <td> <?php echo $tiene_plano; ?> </td>
            <th class="specalt"> Fecha Plano: </th> <td> <?php echo $fecha_plano; ?> </td>
        </tr>

        <tr>
            <th colspan="4"> DATOS DE GACETA  </th>
        </tr>
        <tr>
            <th class="specalt"> Nro de Gaceta: </th> <td> <?php echo $numero_gaceta; ?> </td>  
            <th class="specalt"> Fecha Publicacion: </th> <td> <?php echo $fecha_gaceta; ?> </td> 
        </tr>

        <tr>
            <th colspan="4"> OBSERVACIONES  </th>
        </tr>
        <tr>
            <th class="specalt"> Observacion de concesion: </th> <td colspan="3"> <?php echo $observacion_concesion; ?> </td>        
        </tr>
        <?php if ($estado_concesion === 'EXTINTO') { ?>
            <tr>
                <th class="specalt"> Observacion de extincion: </th> <td colspan="3"> <?php echo $observacion_extincion; ?> </td>
            </tr>
        <?php } ?>
    </table>

    <?php
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-Mostrar datos para PERTENENCIAS //////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($tipo_concesion == 'PERTENENCIA') {
?>
    <table cellspacing="0" align="center" border="0">
        <tr>
            <th colspan="4"> DATOS DE CONCESION  </th>
        </tr>
        <tr>
            <th class="specalt"> Nombre: </th> <td colspan="3"> <h3><?php echo $nombre_concesion; ?></h3> </th>            
        </tr>
        <tr>
            <th class="specalt"> Nro Inscripcion:</th> <td> <?php echo $numero_formulario; ?> </td>
            <th class="specalt"> Nro Padron Nacional: </th> <td> <?php echo $padron_nacional; ?> </td>
        </tr>    
        <tr>
            <th class="specalt"> Fecha Adjudicacion: </th> <td> <?php echo $FECADJ; ?> </td>
            <th class="specalt"> Fecha Titulo: </th> <td> <?php echo $FECTIT; ?> </td>
        </tr>
        <tr>
            <th class="specalt"> Fecha Inscripcion: </th> <td> <?php echo $fecha_inscripcion; ?> </td>
            <th class="specalt"> Regional:</th> <td> <?php echo $regional; ?> </td>
        </tr>
        <tr>
            <th class="specalt"> Tipo: </th> <td> <?php echo $tipo_concesion; ?> </td>
            <th class="specalt"> Estado: </th> <td> <?php echo $estado_concesion; ?> </td>
        </tr>

        <tr>
            <th colspan="4"> DATOS DE CONCESIONARIO  </th>
        </tr>
        <tr>
            <th class="specalt"> Nombre: </th> <td colspan="3">
                <?php
                if (!empty($nombre_empresa))
                    echo $nombre_empresa;
                else
                    echo $nombre_persona . ' ' . $paterno_persona . ' ' . $materno_persona;
                ?> </td>            
        </tr>
        <tr>
            <th class="specalt"> Nro NIT: </th> <td> <?php echo $numeroNit = ''; ?> </td>  
            <th class="specalt"> Telefono: </th> <td> <?php echo $telefono = ''; ?> </td> 
        </tr>
        <tr>
            <th class="specalt"> Direccion: </th> <td colspan="3"> <?php echo $DIRECCION; ?> </td>
        </tr>


        <tr>
            <th colspan="4"> DATOS DE UBICACION  </th>
        </tr>
        <tr>
            <th class="specalt"> Departamento: </th> <td> <?php echo $departamento; ?> </td>  
            <th class="specalt"> Provincia: </th> <td> <?php echo $provincia; ?> </td> 
        </tr>
        <tr>
            <th class="specalt"> Canton: </th> <td> <?php echo $canton; ?> </td>  
            <th class="specalt"> Codigo Municipio: </th> <td> <?php echo $codigo_municipio; ?> </td> 
        </tr>

        <tr>
            <th colspan="4"> DATOS DE SOLICITUD  </th>
        </tr>
        <tr>
            <th class="specalt"> Asignadas: </th> <td> <?php echo $cantidad_asignada . ' [' . $unidad . ']'; ?> </td> 
            <th class="specalt"> Solicitadas: </th> <td> <?php echo $cantidad_solicitada . ' [' . $unidad . ']'; ?> </td>          
        </tr>
        <tr>
            <th class="specalt"> Plano o crokis: </th> <td> <?php echo ucfirst($PLANO_O_CROQUIS); ?> </td>
            <th class="specalt"> Tipo concesion: </th> <td> <?php echo $TIPO_DE_CONCESION; ?> </td>
        </tr>
        
        <tr>
            <th colspan="4"> DATOS ADICIONALES  </th>
        </tr>
        <tr>
            <th class="specalt"> Nacionalizada: </th> <td> <?php echo $NACIONALIZADA=='t'?'SI':'NO'; ?> </td> 
            <th class="specalt"> Graficada: </th> <td> <?php echo $GRAFICADO=='t'?'SI':'NO'; ?> </td>          
        </tr>
        <tr>
            <th class="specalt"> Trabajo de campo: </th> <td> <?php echo $TRABAJO_DE_CAMPO=='t'?'SI':'NO'; ?> </td>
            <th class="specalt"> Colindancia: </th> <td> <?php echo $COLINDANCIA=='t'?'SI':'NO'; ?> </td>
        </tr>


        <tr>
            <th colspan="4"> OBSERVACIONES  </th>
        </tr>
        <tr>
            <th class="specalt"> Observacion de concesion: </th> <td colspan="3"> <?php echo $observacion_concesion; ?> </td>        
        </tr>
        <?php if ($estado_concesion === 'EXTINTO') { ?>
            <tr>
                <th class="specalt"> Observacion de extincion: </th> <td colspan="3"> <?php echo $observacion_extincion; ?> </td>
            </tr>
        <?php } ?>
    </table>

    <?php
}
?>
