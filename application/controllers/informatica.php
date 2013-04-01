<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Informatica extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('funciones_comunes');
        $this->load->model('modelo_informatica', '', TRUE);
    }

    function _vista_principal($output = null) {
        $this->load->view('index_informatica.php', $output);
    }

    function index() {
        $this->_vista_principal((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }
//=======================================================================================================================
//- Funciones que muestra la informacion informacion de las concesiones con la opciones de ver datos, editar, eliminar
//=======================================================================================================================
    function informacion_concesion() {
        //$crud = null;
        $crud = new grocery_CRUD();
        $crud->order_by('estado_concesion','desc');
        $crud->set_table('vista_concesion_minera');
        $crud->set_primary_key('id_concesion_minera', 'vista_concesion_minera');
        $crud->set_subject('Patentes');
        $crud->columns('numero_formulario', 'padron_nacional', 'nombre_concesion','concesionario');
        $crud->callback_column('nombre_concesion', array($this, '_concesion'));        
        $crud->display_as('numero_formulario', 'Nro Formulario Inscripcion')
                ->display_as('padron_nacional', 'Nro Padron Nacional')
                ->display_as('nombre_concesion', 'Concesion Minera');
        
        $crud->add_action('Editar Concesion', '', 'informatica/editar_concesion/edit', 'edit-icon')
             ->add_action('Eliminar Concesion', '', 'informatica/eliminar_concesion/edit', 'delete-icon')
             ->add_action('Ver mas Informacion', base_url('estilo/images/mas_informacion.png'), 'informatica/informacion_concesionPatentes');
        $crud->unset_operations();

        $output = $crud->render();
        $output->titulo = 'CONCESIONES MINERAS';
        $this->_vista_principal($output);
    }

    function eliminar_concesion() {
        $crud = new grocery_CRUD();
        $crud->set_table('concesion_minera');
        $crud->set_primary_key('id_concesion_minera', 'concesion_minera');
        $crud->set_subject('Concesion Minera');
        $crud->display_as('numero_formulario', 'Nro Formulario Inscripcion')
                ->display_as('padron_nacional', 'Nro Padron Nacional')
                ->display_as('nombre_concesion', 'Concesion Minera')
                ->display_as('nombre_empresa', 'Concesionario');

        $crud->fields('numero_formulario', 'padron_nacional', 'nombre_concesion', 'fecha_extincion', 'estado_concesion', 'observacion_extincion');
        $crud->field_type('numero_formulario', 'readonly')
                ->field_type('padron_nacional', 'readonly')
                ->field_type('nombre_concesion', 'readonly')
                ->field_type('fecha_extincion', 'date')
                ->field_type('estado_concesion', 'hidden', 'EXTINTO');
        $crud->required_fields('fecha_extincion', 'estado_concesion', 'observacion_extincion');
        
        //$crud->set_lang_string('update_success_message', 'Los datos an sido correctamente Almacenados en la Base de datos.<br/> <div style="display:none">');

        $crud->unset_add()
                ->unset_delete()
                ->unset_print()
                ->unset_export();
        $estado = $crud->getState();
        if ($estado === 'edit' || $estado === 'update_validation') {
            $output = $crud->render();
            $output->titulo = 'CONCESIONES MINERAS';
            $this->_vista_principal($output);
        } else {
            unset($crud);
            unset($output);
            //$this->informacion_concesion();
            redirect('/informatica/informacion_concesion/', 'refresh');
        }
    }
    
    function editar_concesion(){
        $crud = new grocery_CRUD();
        $crud->set_table('concesion_minera');
        $crud->set_primary_key('id_concesion_minera', 'concesion_minera');
        $crud->set_subject('Patentes');
        $crud->columns('numero_formulario', 'padron_nacional', 'nombre_concesion','concesionario');
        $crud->callback_column('nombre_concesion', array($this, '_concesion'));
        $crud->display_as('numero_formulario', 'Nro Formulario Inscripcion')
                ->display_as('padron_nacional', 'Nro Padron Nacional')
                ->display_as('nombre_concesion', 'Concesion Minera');
        $crud->unset_fields('id_concesion_minera','id_numins');
        //$crud->add_action('Editar Concesion', '', 'informatica/editar_concesion/edit', 'edit-icon');
             $crud->add_action('Eliminar Concesion', '', 'informatica/eliminar_concesion/edit', 'delete-icon')
                ->add_action('Ver mas Informacion', base_url('estilo/images/mas_informacion.png'), 'informatica/informacion_patentes');
        $crud->set_primary_key('id_regional', 'regional');
        $crud->set_relation('id_regional', 'regional', 'nombre_regional');
        $crud->field_type('unidad', 'enum', array('CUADRICULA', 'HAS', 'M2'))
                //->field_type('departamento', 'enum', array('LA PAZ', 'ORURO', 'POTOSI', 'COCHABAMBA', 'TARIJA', 'SUCRE', 'SANTA CRUZ', 'BENI', 'PANDO'))
                ->field_type('fecha_inscripcion', 'date')
                ->field_type('fecha_gaceta', 'date')
                ->field_type('fecha_resolucion', 'date')
                ->field_type('fecha_plano', 'date')
                ->field_type('fecha_extincion', 'date')
                ->field_type('estado_concesion', 'enum', array('VIGENTE', 'EXTINTO', 'TRAMITE'))
                ->field_type('tipo_concesion', 'enum', array('CUADRICULA', 'PERTENENCIA'));

         $crud->unset_add()
                ->unset_delete()
                ->unset_print()
                ->unset_export();
         
        $estado = $crud->getState();
        if ($estado === 'edit' || $estado === 'update_validation') {
            $output = $crud->render();
            $output->titulo = 'CONCESIONES MINERAS';
            $this->_vista_principal($output);
        } else {
            $crud = null;
            $this->informacion_concesion();
        }
    }

    // FUNCIONES DE callback_column -------------------------------------------------------------------------	
    function _concesion($value, $row) {
        $html = '<div class="message error">';
        if (strtolower($row->estado_concesion) == 'vigente')
            $html = '<div class="message success">';

        $html.= 'Concesion: <b>' . strtoupper($row->nombre_concesion) . '</b><br />';
        $html.= 'Tipo : <b>' . strtoupper($row->tipo_concesion) . '</b><br />';
        //$html.= 'Cantidad Asignada : <b>' . strtoupper($row->cantidad_asignada) . ' ' . $row->unidad . '</b><br />';
        //$html.= 'Departamento : <b>' . strtoupper($row->departamento) . '</b><br />';
        //$html.= 'Provincia : <b>' . strtoupper($row->provincia) . '</b><br />';
        //$html.= 'Canton/Municipio : <b>' . strtoupper($row->canton) . '</b><br />';
        //$html.= 'Codigo Municipio : <b>' . strtoupper($row->codigo_municipio) . '</b><br />';
        $html.= '<p><strong>Estado : ' . strtoupper($row->estado_concesion) . '</strong></p></div>';
        return $html;
    }

    
//=======================================================================================================================
//-- Verifica las concesion POR CUADRICULAS que no tengan completo sus pagos de patentes
//=======================================================================================================================
    function verificar_concesinesConPagosIncompletosDePatentes() {
        $datosConcesionesVigentes = $this->modelo_informatica->concesiones_vigentes_con_fechaResolcion();
        if (!$datosConcesionesVigentes) {
            echo 'No Se encontraron datos de concesiones vigentes';
            exit;
        } else {
            $nro = 0;
            $this->load->library('table');
            $this->table->set_heading('Nro', 'Nro Inscripcion', 'Nombre concesion', 'fecha Resolucion', 'Numero Resolucion', 'Gestiones Pagadas', 'Gestiones No Pagadas', 'Accion'
            );
            $enviarDatos['tipoReporte'] = 'REPORTE PATENTES';
            $enviarDatos['tituloReporte'] = 'CONCESIONES MINERAS POR CUADRICULA <br /> QUE NO TIENEN AL DIA SUS PAGOS DE PATENTES <br /><br />';

            foreach ($datosConcesionesVigentes->result() AS $concesion) {
                $gestionResolucionOriginal = substr($concesion->fecha_resolucion, 0, 4);
                $id_concesion_minera = $concesion->id_concesion_minera;
                $datosPatentes = $this->modelo_informatica->patentes_concesiones($id_concesion_minera);
                $gestionResolucion = $gestionResolucionOriginal;
                $parametroGestion = 2012; // Gestion en la cual se define el calculo
                if ($gestionResolucion <= $parametroGestion)
                    $gestionResolucion = $parametroGestion;

                if ($datosPatentes) {
                    $gestionesPagadas = '';
                    $gestionesNoPagadas = '';
                    $gestionesNoPagadas2='';
                    for ($gestion = $gestionResolucion; $gestion <= 2012; $gestion++) {
                        $sw = 0;
                        foreach ($datosPatentes->result() as $row) {
                            $importeGestion = $row->importe_gestion;
                            if ($gestion == $importeGestion)
                                $sw = 1;
                        }
                        if ($sw == 1)
                            $gestionesPagadas.= '(' . $gestion . ') ';
                        else             
                                //if($gestionResolucion == $gestionResolucionOriginal)
                                    $gestionesNoPagadas.= '(' . $gestion . ')';                                    
                                //else                                    
                                    //$gestionesNoPagadas2 .= '(' . $gestion . ')';
                                
                            
                    }
                    if ($gestionesNoPagadas != '')
                    //if ($gestionesNoPagadas != '' || $gestionesNoPagadas2=='(2012)')
                        //if ($gestionesNoPagadas2!= '') $gestionesNoPagadas=$gestionesNoPagadas2;
                        $this->table->add_row(++$nro, $concesion->numero_formulario, $concesion->nombre_concesion, date('d-m-Y', strtotime($concesion->fecha_resolucion)), $concesion->numero_resolucion, $gestionesPagadas, $gestionesNoPagadas
                                , '<a href="' . site_url('informatica/informacion_patentes/' . $id_concesion_minera) . '">Ver datos </a>'
                        );
                    
                }else {
                    //echo $id_concesion_minera;exit;
                    $this->table->add_row(++$nro, $concesion->numero_formulario, $concesion->nombre_concesion, date('d-m-Y', strtotime($concesion->fecha_resolucion)), $concesion->numero_resolucion, '-', 'No tiene ningun pago registrado en la base de datos'
                            , '<a href="' . site_url('informatica/informacion_patentes/' . $id_concesion_minera) . '">Ver datos </a>'
                    );
                }
            }

            $enviarDatos['reporte'] = $this->table->generate(); //tabla con resultado de reporte diario
            $datos['output'] = $this->load->view('vista_reportes.php', $enviarDatos, TRUE);
            $this->_vista_principal($datos);
        }
    }
//=======================================================================================================================
//-- Verifica las concesion POR PERTENENCIA que no tengan completo sus pagos de patentes
//=======================================================================================================================
    function verificar_concesinesConPagosIncompletosDePatentesPertenencia() {
        $datosConcesionesVigentes = $this->modelo_informatica->concesiones_vigentes_con_fechaInscripcion();
        if (!$datosConcesionesVigentes) {
            echo 'No Se encontraron datos de concesiones vigentes con fecha de resolucion';
            exit;
        } else {
            $nro = 0;
            $this->load->library('table');
            $this->table->set_heading('Nro', 'Nro Inscripcion', 'Nombre concesion', 'Concesionario', 'Nacionalizada', 'fecha Inscripcion', 'Gestiones Pagadas', 'Gestiones No Pagadas', 'Accion'
            );

            $enviarDatos['tipoReporte'] = 'REPORTE PATENTES';
            $enviarDatos['tituloReporte'] = 'CONCESIONES MINERAS POR PERTENENCIA <br /> QUE NO TIENEN AL DIA SUS PAGOS DE PATENTES <br /><br />';

            foreach ($datosConcesionesVigentes->result() AS $concesion) {
                $gestionResolucion = substr($concesion->fecha_inscripcion, 0, 4);
                $id_concesion_minera = $concesion->id_concesion_minera;
                $datosPatentes = $this->modelo_informatica->patentes_concesiones($id_concesion_minera);

                if ($gestionResolucion <= 2010)
                    $gestionResolucion = 2010;

                if ($datosPatentes) {
                    $gestionesPagadas = '';
                    $gestionesNoPagadas = '';
                    for ($gestion = $gestionResolucion; $gestion <= 2012; $gestion++) {
                        $sw = 0;
                        foreach ($datosPatentes->result() as $row) {
                            $importeGestion = $row->importe_gestion;
                            if ($gestion == $importeGestion)
                                $sw = 1;
                        }
                        if ($sw == 1)
                            $gestionesPagadas.= '(' . $gestion . ') ';
                        else
                            $gestionesNoPagadas.= '(' . $gestion . ') ';
                    }
                    if ($gestionesNoPagadas != '')
                        $this->table->add_row(++$nro, $concesion->numero_formulario, $concesion->nombre_concesion, $concesion->nombre_empresa == NULL ? $concesion->nombre_persona . ' ' . $concesion->paterno_persona . ' ' . $concesion->materno_persona : $concesion->nombre_empresa, $concesion->NACIONALIZADA == 't' ? 'SI' : 'NO', date('d-m-Y', strtotime($concesion->fecha_inscripcion)), $gestionesPagadas, $gestionesNoPagadas
                                , '<a href="' . site_url('informatica/informacion_patentes/' . $id_concesion_minera) . '">Ver datos </a>'
                        );
                }else {
                    //echo $id_concesion_minera;exit;
                    $this->table->add_row(++$nro, $concesion->numero_formulario, $concesion->nombre_concesion, $concesion->nombre_empresa, $concesion->NACIONALIZADA == 't' ? 'SI' : 'NO', date('d-m-Y', strtotime($concesion->fecha_inscripcion)), '-', 'No tiene ningun pago registrado en la base de datos'
                            , '<a href="' . site_url('informatica/informacion_patentes/' . $id_concesion_minera) . '">Ver datos </a>'
                    );
                }
            }

            $enviarDatos['reporte'] = $this->table->generate(); //tabla con resultado de reporte diario
            $datos['output'] = $this->load->view('vista_reportes.php', $enviarDatos, TRUE);
            $this->_vista_principal($datos);
        }
    }
//=======================================================================================================================
//-- Verificar concesiones con pago menos
//=======================================================================================================================
    function verificar_concesinesConPagosMenos() {
        // 1.Lista todas las concesione vigentes por cuadricula y pertenencia
        $datosConcesionesVigentes = $this->modelo_informatica->concesiones_vigentes();
        if (!$datosConcesionesVigentes) {
            echo 'No Se encontraron datos de concesiones vigentes';
            exit;
        } else {
            $nro = 0;
            $this->load->library('table');
            $this->table->set_heading('Nro', 'id_concesion_minera','Nro Inscripcion', 'Nombre concesion', 'fecha Resolucion', 'Numero Resolucion', 'Gestiones Pagadas','Gestiones No Pagadas', 'Accion');
            $enviarDatos['tipoReporte'] = 'REPORTE INFORMATICA';
            $enviarDatos['tituloReporte'] = 'CONCESIONES MINERAS <br /> QUE REALIZARON PAGO MENOS DE PATENTES EN LA GESTION 2012<br /><br />';
            // 2. Verifica el pago de patentes de cada una de las cancesiones mineras
            foreach ($datosConcesionesVigentes->result() AS $concesion) {
                //--Verifica si es cuadricula o pertenencia, para extraer la fecha de resolucion o inscripcion
                if ($concesion->tipo_concesion == 'CUADRICULA')
                    $gestionResolucion = substr($concesion->fecha_resolucion, 0, 4);
                if ($concesion->tipo_concesion == 'PERTENENCIA')
                    $gestionResolucion = substr($concesion->fecha_inscripcion, 0, 4);

                $id_concesion_minera = $concesion->id_concesion_minera;
                $datosPatentes = $this->modelo_informatica->patentes_calculoPago($id_concesion_minera); //Verifica el pago de patentes de una concesion

                $gestionProgresivo = $gestionResolucion;
                if ($gestionResolucion <= 2012)
                    $gestionResolucion = 2012;
                //--Verifica si tene pagos de patentes
                if ($datosPatentes) {
                    $gestionesPagadas = '';
                    $gestionesNoPagadas = '';
                    //$progresivo = $datosPatentes->num_rows();
                    //--Verifica si tiene todas las gestion pagadas
                    $pagosMenos = 'NO';
                    for ($gestion = $gestionResolucion; $gestion <= 2012; $gestion++) {
                        $sw = 0;
                        $pagoCalculadoSistema = 0;
                        $importe_sistema = 0;
                        $importe_cancelado = 0;
                        $diferencia = 0;
                        foreach ($datosPatentes->result() as $row) {
                            $importeGestion = $row->importe_gestion;
                            if ($gestion == $importeGestion) {
                                $sw = 1;
                                $datosTarifa = $this->modelo_informatica->patente_tarifa($row->fecha_pago); //verifica la tarifa con la que pago
                                if (!$datosTarifa) {
                                    $importe_sistema = 'No tiene fecha Pago';
                                } else {
                                    $cantidadAsignada = $concesion->cantidad_asignada;
                                    switch ($concesion->tipo_concesion) {
                                        case 'CUADRICULA':
                                            $progresivo = $gestion - $gestionProgresivo;
                                            if ($progresivo >= 5)
                                                $tarifa = $datosTarifa->importe_cuadricula_progresivo;
                                            else
                                                $tarifa = $datosTarifa->importe_cuadricula;
                                            break;
                                        case 'PERTENENCIA':
                                            if ($concesion->unidad == 'M2')
                                                $cantidadAsignada = ($cantidadAsignada / 10000); //Convierte a Hectarias si son M2
                                            if ($cantidadAsignada > 1000)
                                                $tarifa = $datosTarifa->importe_pertenencia_progresivo;
                                            else
                                                $tarifa = $datosTarifa->importe_pertenencia;
                                            break;
                                    }

                                    //$importe_sistema = $tarifa * $cantidadAsignada;

                                    $importe_cancelado = $row->importe;
                                    $importe_sistema = $this->modelo_informatica->operador_aritmetico($tarifa, $cantidadAsignada, '*');
                                    //$diferencia = $importe_sistema - $importe_cancelado;
                                    $diferencia = $this->modelo_informatica->operador_aritmetico($importe_sistema, $importe_cancelado, '-');
                                    //Verifica si el pago que realizo es correcto
                                    $esPagoMenos = $this->modelo_informatica->operador_aritmetico($importe_cancelado, $importe_sistema, '<');
                                    if ($esPagoMenos == 't') {
                                        $pagosMenos = 'SI';
                                        $diferencia = '<div style="color:red;">' . $diferencia . '</div>';
                                    }
                                }
                            }
                        }
                        if ($sw == 1) {
                            $gestionesPagadas.= 'gestion: ' . $gestion;
                            $gestionesPagadas.= '<br />importe_Cancelado: ' . $importe_cancelado;
                            $gestionesPagadas.= '<br />Importe_Sistema: ' . $importe_sistema;
                            $gestionesPagadas.= '<br />Diferencia: ' . $diferencia;
                            $gestionesPagadas.= '<hr />';
                        } else {
                            $gestionesNoPagadas.= '(' . $gestion . ') ';
                        }
                    }
                    //--Muestra solo las concesiones conpago Menos
                    //if ($gestionesNoPagadas != '')
                    if ($pagosMenos == 'SI')
                        $this->table->add_row(++$nro, $concesion->id_concesion_minera,$concesion->numero_formulario, $concesion->nombre_concesion, date('d-m-Y', strtotime($concesion->fecha_resolucion)), $concesion->numero_resolucion, $gestionesPagadas, $gestionesNoPagadas
                                , '<a href="' . site_url('informatica/informacion_patentes/' . $id_concesion_minera) . '">Ver datos </a>'
                        );
                }else {
                    //echo $id_concesion_minera;exit;
                    $this->table->add_row(++$nro, $concesion->id_concesion_minera, $concesion->numero_formulario, $concesion->nombre_concesion, date('d-m-Y', strtotime($concesion->fecha_resolucion)), $concesion->numero_resolucion, '-', 'No tiene ningun pago registrado en la base de datos'
                            , '<a href="' . site_url('informatica/informacion_patentes/' . $id_concesion_minera) . '">Ver datos </a>'
                    );
                }
            }

            $enviarDatos['reporte'] = $this->table->generate(); //tabla con resultado de reporte diario
            $datos['output'] = $this->load->view('vista_reportes.php', $enviarDatos, TRUE);
            $this->_vista_principal($datos);
        }
    }

//=======================================================================================================================
//-- Cambiar Contraseña
//=======================================================================================================================

    function usuario() {
        $this->load->library('funciones_comunes');
        $usuario = new Funciones_comunes();
        $output = $usuario->usuario_cambiarPassword();
        $this->_vista_principal($output);
    }

    function mensaje() {
        $this->load->library('funciones_comunes');
        $mensaje = new Funciones_comunes();
        $output = $mensaje->mensaje();
        $this->_vista_principal($output);
    }

    
 //=======================================================================================================================
//-- Prueva Vistas
//=======================================================================================================================   
     function informacion_concesion_vista() {
        $crud = null;
        $crud = new grocery_CRUD();
        $crud->set_table('vista_concesion_minera');
        $crud->set_primary_key('id_concesion_minera', 'vista_concesion_minera');
        $crud->set_subject('VISTA');
        $crud->columns('numero_formulario', 'padron_nacional', 'nombre_concesion', 'nombre_empresa','concesionario');
        
        $crud->unset_add()
                ->unset_delete()
                ->unset_print()
                ->unset_export();

        $output = $crud->render();
        $output->titulo = 'CONCESIONES MINERAS';
        $this->_vista_principal($output);
    }
        function informacion_concesionPatentes($id_concesion_minera) {        
        $funcionesComunes = new funciones_comunes();
        $enviarContenido['contenido']=array('Datos de Concesion'=>$funcionesComunes->informacion_concesionMinera($id_concesion_minera),
                                            'Datos de Patentes'=>$funcionesComunes->informacion_patentes($id_concesion_minera)
                                            );
        $output->output = boton('volver').$this->load->view('vista_pestana.php',$enviarContenido, TRUE);
        $output->titulo = 'BUSCAR CONCESIONES MINERAS';
        $this->_vista_principal($output);
    }
}