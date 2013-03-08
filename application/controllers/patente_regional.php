<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Patente_regional extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->library('session');
        $this->load->helper('sergeotecmin');
        $this->load->model('modelo_patente_regional', '', TRUE);
    }

    function _vista_principal($output = null) {
        $this->load->view('index_patenteRegional.php', $output);
    }

    function index() {
        $this->_vista_principal((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    function patentes() {
        $this->db->where('estado', 'A');
        $datosGestion = $this->db->get('importe_patente')->row();
        $fechaFinalPago = $datosGestion->fecha_final_pago;
        $fechaActual = date('Y-m-d');

        if ($fechaActual > $fechaFinalPago) {
            $retornar->output = '<div class="message error">No se puede emitir formularios de pago de patentes:<p><strong> La fecha limite de pago de patenes fue el ' . $fechaFinalPago . '</strong></p></div>';
            $this->_vista_principal($retornar);
        } else {

            $crud = new grocery_CRUD();
            $crud->order_by('estado_concesion', 'desc');
            //$crud->order_by('fecha_resolucion');                
            $crud->set_table('vista_concesion_minera');
            $crud->set_primary_key('id_concesion_minera', 'vista_concesion_minera');
            $crud->set_subject('Patentes');
            $crud->columns('numero_formulario', 'padron_nacional', 'nombre_concesion', 'concesionario');
            $crud->add_action('', '', '', 'garmaser', array($this, '_linkImprimir')); //garmaser:: se pone para que imprima tal como la funcion devuelve
            $crud->callback_column('nombre_concesion', array($this, '_concesion'));
            $crud->callback_column('concesionario', array($this, '_concesionario'));
            //$crud->callback_column('imprimir', array($this, '_linkImprimir'));
            $crud->display_as('numero_formulario', 'Nro Formulario Inscripcion')
                    ->display_as('padron_nacional', 'Nro Padron Nacional')
                    ->display_as('nombre_concesion', 'Concesion Minera');
            $crud->unset_operations();

            $output = $crud->render();
            $this->_vista_principal($output);
        }
    }

//=============================================================================================================
// Funciones para generar la BOLETA DE PAGO DE PATENTES
//=============================================================================================================
    function patentes_controlarPagoDePatentes($id_concesion_minera) {
        //-- Saca los datos de una concesion
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $datosConcesion = $this->db->get('concesion_minera')->row();

        //-- Saca los datos de importe gestion vigente
        $this->db->where('estado', 'A');
        $datosImporte = $this->db->get('importe_patente')->row();
        $gestionVigente = $datosImporte->gestion;

        //-- recupera datos que se usaran para controlar
        if (strtolower($datosConcesion->tipo_concesion) === 'cuadricula')
            $gestionResolucion = substr($datosConcesion->fecha_resolucion, 0, 4);
        else
            $gestionResolucion = substr($datosConcesion->fecha_inscripcion, 0, 4);
        ///////////////////////////////////////////////////////////////////////
        //-- 1. Controla si una concesion tiene todas sus patentes canceladas
        ///////////////////////////////////////////////////////////////////////
        $html = '';
        //-- Lista las patentes pagadas de una concesion
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $patentes = $this->db->get('patentes');
        //-- Verifica si pago todas las gestiones desde su gestion de resolucion hasta la fecha
        $gestion = 0;
        $gestionesPagadas = '';
        $gestionesNoPagadas = '';
        for ($gestion = $gestionResolucion; $gestion < $gestionVigente; $gestion++) {
            $sw = 0;
            foreach ($patentes->result() as $row) {
                $importeGestion = $row->importe_gestion;
                if ($gestion == $importeGestion)
                    $sw = 1;
            }
            if ($sw == 1)
                $gestionesPagadas.= '<br />' . $gestion;
            else
                $gestionesNoPagadas.= '<br />' . $gestion;
        }

        if (!($gestionesNoPagadas == '')) {
            $linkVolver = '<a id="volver" href="#" title="Volver a generar nueva busqueda"> <img src="' . base_url() . 'estilo/images/boton_volver.png" width="119" height="41" alt="Volver"/> </a>';
            //mensaje error
            $titulo = 'HISTORIAL DE PAGOS INCOMPLETO';
            $descripcion = 'No se puede emitir formulario, por falta de pagos de patentes:';
            $descripcion.= '<table><tr><td><b>Nro Inscripcion:</b></td>  <td> ' . $datosConcesion->numero_formulario . '</td></tr>';
            $descripcion.= '<tr><td><b>Nombre Concesion:</b></td>  <td> ' . $datosConcesion->nombre_concesion . '</td></tr>';
            $descripcion.= '<tr><td><b>Gestion que se emitio la resolucion:</b></td>  <td> ' . $gestionResolucion . '</td></tr>';
            $descripcion.= '<tr><td> <b>Gestion actual: </b></td>  <td>' . $gestionVigente . '</td></tr></table>';
            $error = 'Patentes SIN PAGAR: <br /><br /> GESTION ' . $gestionesNoPagadas;

            $retornar->output = $linkVolver . ' ' . mensaje_error($titulo, $error, $descripcion);
            $this->_vista_principal($retornar);
        } else {
            //--2. Controla si ya REALIZO EL PAGO de la gestion actual y la siguiente
            $pagosAdelantados = 5; // determina el numero de pagos que puede realizar            
            $this->load->library('table');
            $this->table->set_heading('GESTION', 'EXTENCION ASIGNADA', 'PATENTE ' . $gestionVigente, 'TIPO', 'TOTAL','OTRA INFORMACION', 'ESTADO');
            
            for ($i = 0; $i <= $pagosAdelantados; $i++) {
                $gestion = $gestionVigente + $i;
                $this->db->where('id_concesion_minera', $id_concesion_minera);
                $this->db->where('importe_gestion', "$gestion");
                $realizoPago = $this->db->get('patentes');
                $unidad = $datosConcesion->unidad;
                $cantidadAsignada = $datosConcesion->cantidad_asignada;
                //--2.1 Controla si le corresponde pago SIMPLE o PROGRESIVO
                $datosSimpleProgresivo = $this->_calcular_simpleProgresivo($datosConcesion, $datosImporte, $gestion, $gestionResolucion);
                $importe = $datosSimpleProgresivo['importe'];
                $tipoImporte = $datosSimpleProgresivo['tipoImporte'];
                $importeCalculado = $datosSimpleProgresivo['importeCalculado'];                
                if ($realizoPago->num_rows() > 0) {
                    $realizoPago = $realizoPago->row();
                    $estado = $realizoPago->estado_formulario_pago_patente;
                    switch ($estado) {
                        case 'PAGADO':
                            //-- 
                            $datosPagoRealizado = $this->modelo_patente_regional->pago_patentesPorGestion($id_concesion_minera, $gestion);
                            $importeCancelado = $datosPagoRealizado->importe_cancelado;
                            $fechaPago = $datosPagoRealizado->fecha_pago;

                            $datosImportePorGestion = $this->modelo_patente_regional->datos_importePorGestion($fechaPago);

                            //--2.1 Controla si le corresponde pago SIMPLE o PROGRESIVO
                            $datosSimpleProgresivo = $this->_calcular_simpleProgresivo($datosConcesion, $datosImportePorGestion, $gestion, $gestionResolucion);
                            $importe2 = $datosSimpleProgresivo['importe'];
                            $tipoImporte2 = $datosSimpleProgresivo['tipoImporte'];
                            $importeCalculado2 = $datosSimpleProgresivo['importeCalculado'];
                            if ($importeCancelado >= $importeCalculado2)
                                $this->table->add_row(   $realizoPago->importe_gestion, $cantidadAsignada . ' [' . $unidad . ']', $importe2, $tipoImporte2, $importeCancelado,' Fecha de pago : '.$fechaPago, 'Registrado en el sistema');
                            else
                                $this->table->add_row($realizoPago->importe_gestion, $cantidadAsignada . ' [' . $unidad . ']', $importe, $tipoImporte, ($importeCalculado - $importeCancelado) , 'Cancelado : '.$importeCancelado.'<br />Fecha pago : '.$fechaPago ,'<button class="emitir_formulario">Emitir Reintegro</button>');
                            break;
                        case 'EMITIDO':
                                $this->table->add_row($gestion, $cantidadAsignada . ' [' . $unidad . ']', $importe, $tipoImporte, $importeCalculado, '','<button class="emitir_formulario">Emitir otro formulario</button>');
                            break;
                    }
                }else {
                    $this->table->add_row($gestion, $cantidadAsignada . ' [' . $unidad . ']', $importe, $tipoImporte, $importeCalculado, '','<button class="emitir_formulario">Realizar pago</button>');
                }
            }
            $enviarDatos['tablaPagos'] = $this->table->generate();
            //$enviarDatos['personas'] = json_encode($this->modelo_patente_regional->personas());
            $datos['output'] = $this->load->view('patenteRegional_pagoPatentes.php', $enviarDatos, true);
            $this->_vista_principal($datos);
        }
    }
    function patentes_persona(){
        $personas = $this->modelo_patente_regional->personas();
        echo json_encode($personas);
    }
    
    function patentes_encontrarPersona($idPersona){
        //$idPersona=$this->input->post('id');
        $enviarDatos['persona'] = $this->modelo_patente_regional->persona($idPersona);
        $this->load->view('patenteRegional_persona.php', $enviarDatos);
    }
    
    function patentes_mineral(){
        //$idPersona=$this->input->post('id');
        $minerales = $this->modelo_patente_regional->minerales();
        echo json_encode($minerales);
    }
    
    function _calcular_simpleProgresivo($datosConcesion, $datosImporte, $gestion, $gestionResolucion) {
        ///////////////////////////////////////////////////////////////////////
        //-- Controla si le corresponde pago SIMPLE o PROGRESIVO
        ///////////////////////////////////////////////////////////////////////
        $cantidadAsignada = $datosConcesion->cantidad_asignada;
        switch (strtolower($datosConcesion->tipo_concesion)) {
            case 'cuadricula':
                if ($datosConcesion->unidad === 'HAS')
                    $cantidadAsignada = $cantidadAsignada / 25; //convierte a cuadiculas si son HAS
                $gestionesVigentes = $gestion - $gestionResolucion;    //saca el numero de gestiones de vigencia
                $gestionesVigentes = 1 + $gestionesVigentes;                        // sumamos mas uno, para tomar en cuenta la gestion de la resolucion
                if ($gestionesVigentes >= $datosImporte->gestiones_aplicables_progesivo) {
                    //--Define el costo de importe PROGRESIVO para CUADRICULA
                    $tipoImporte = 'PROGRESIVO';
                    $importe = $datosImporte->importe_cuadricula_progresivo;
                } else {
                    //--Define el costo SIMPLE para CUADRICULA
                    $tipoImporte = 'SIMPLE';
                    $importe = $datosImporte->importe_cuadricula;
                }
                break;
            case 'pertenencia':
                if ($datosConcesion->unidad === 'M2')
                    $cantidadAsignada = $cantidadAsignada / 10000; //convierte a HAS si son M2
                if ($cantidadAsignada > 1000) {
                    //--Define el costo de importe PROGRESIVO para CUADRICULA
                    $tipoImporte = 'PROGRESIVO';
                    $importe = $datosImporte->importe_pertenencia_progresivo;
                } else {
                    //--Define el costo SIMPLE para CUADRICULA
                    $tipoImporte = 'SIMPLE';
                    $importe = $datosImporte->importe_pertenencia;
                }
                break;
        }
        $importeCalculado = $importe * $cantidadAsignada;
        $resultado = array('importe' => $importe, 'tipoImporte' => $tipoImporte, 'importeCalculado' => $importeCalculado);

        return $resultado;
    }

    function patentes_datosConcesion() {
        //-- Saca el nombre de concesion
        if ($datosConcesion->nombre_empresa == NULL OR $datosConcesion->nombre_empresa == '')
            $nombreConcesionario = $datosConcesion->nombre_persona . ' ' . $datosConcesion->paterno_persona . ' ' . $datosConcesion->materno_persona;
        else
            $nombreConcesionario = $datosConcesion->nombre_empresa;

        // prepara datos para enviar al formulario
        $datosFormulario['cantidadAsignada'] = $datosConcesion->cantidad_asignada;
        $datosFormulario['nombreConcesionario'] = $nombreConcesionario;
        $datosFormulario['nombreConcesion'] = $datosConcesion->nombre_concesion;
        $datosFormulario['numeroInscripcion'] = $datosConcesion->numero_formulario;
        $datosFormulario['padronNacional'] = $datosConcesion->padron_nacional;
        $datosFormulario['departamento'] = $datosConcesion->departamento;
        $datosFormulario['provincia'] = $datosConcesion->provincia;
        $datosFormulario['canton'] = $datosConcesion->canton;
        $datosFormulario['codigo_municipio'] = $datosConcesion->codigo_municipio;
        $datosFormulario['tipoConcesion'] = $datosConcesion->tipo_concesion;
        $datosFormulario['gestion'] = $datosImporte->gestion;
        $datosFormulario['unidad'] = $datosConcesion->unidad;
        $datosFormulario['progresivo'] = $progresivo;
        $datosFormulario['importe'] = $importe;
        $datosFormulario['importeTotal'] = $importe * $cantidadAsignada;
        $datosFormulario['nit'] = '---';
        $datosFormulario['telefono'] = '---';
        $datosFormulario['resolucion'] = $datosConcesion->numero_resolucion . ' de fecha ' . fecha_literal($datosConcesion->fecha_resolucion, 4);
        $datosFormulario['canton2'] = $datosConcesion->CANTON2;

        $datosInsertar = array('id_concesion_minera' => $id_concesion_minera,
            'importe_gestion' => $datosImporte->gestion,
            'importe' => $datosFormulario['importeTotal'],
            'id_importe_patente' => $datosImporte->id_importe_patente,
            'estado_formulario_pago_patente' => 'EMITIDO',
            'fecha_formulario_pago_patente' => date('Y-m-d H:i:s')
        );
        $this->db->insert('patentes', $datosInsertar); //inserta los datos en la tabla patentes para generar numero de formulario

        $datosFormulario['nroFormularioPagoPatente'] = 'FP' . $id_patentes = $this->db->insert_id(); //recupera el id_patente ingresado
        $datosFormulario['fechaEmision'] = date('d/m/Y');
        $datosFormulario['codigoBarras'] = $datosFormulario['nroFormularioPagoPatente'];

        //-- Genera codigo de barras
        //$this->load->helper(array('barcode39'));
        //$datosFormulario['codigoBarras']=barcode39_2($datosFormulario['nroFormularioPagoPatente']);
        // GENERA PDF
        //$this->load->helper(array('dompdf', 'file'));
        // page info here, db calls, etc.
        //$html = $this->load->view('formulario_pago_patentes.php', $datosFormulario, true);
        //pdf_create($html, 'filename');

        $datos['output'] = $this->load->view('formulario_pago_patentes.php', $datosFormulario, true);
        $this->_vista_principal($datos);
    }

    function patentes_imprimirFormularioDePagoDePatentes($id_concesion_minera) {
        //$this->_controlarFormularioPagoPatentes($id_concesion_minera);
        //-- Saca los datos de una concesion
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $datosConcesion = $this->db->get('concesion_minera')->row();
        //-- Saca los datos de importe gestion vigente
        $this->db->where('estado', 'A');
        $datosImporte = $this->db->get('importe_patente')->row();

        //$resultadoControl = _controlar_formularioPagoPatentes($datosConcesion, $datosImporte);
        //-- recupera datos que se usaran para controlar
        if (strtolower($datosConcesion->tipo_concesion) === 'cuadricula')
            $gestionResolucion = substr($datosConcesion->fecha_resolucion, 0, 4);
        else
            $gestionResolucion = substr($datosConcesion->fecha_inscripcion, 0, 4);
        ///////////////////////////////////////////////////////////////////////
        //-- 1. Controla si una concesion tiene todas sus patentes canceladas
        ///////////////////////////////////////////////////////////////////////
        $html = '';
        //-- Lista las patentes pagadas de una concesion
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $patentes = $this->db->get('patentes');
        //-- Verifica si pago todas las gestiones desde su gestion de resolucion hasta la fecha
        $gestion = 0;
        $gestionesPagadas = '';
        $gestionesNoPagadas = '';
        for ($gestion = $gestionResolucion; $gestion < $datosImporte->gestion; $gestion++) {
            $sw = 0;
            foreach ($patentes->result() as $row) {
                $importeGestion = $row->importe_gestion;
                if ($gestion == $importeGestion)
                    $sw = 1;
            }
            if ($sw == 1)
                $gestionesPagadas.= '<br />' . $gestion;
            else
                $gestionesNoPagadas.= '<br />' . $gestion;
        }

        if (!($gestionesNoPagadas == '')) {
            $linkVolver = '<a id="volver" href="#" title="Volver a generar nueva busqueda"> <img src="' . base_url() . 'estilo/images/boton_volver.png" width="119" height="41" alt="Volver"/> </a>';
            //mensaje error
            $titulo = 'HISTORIAL DE PAGOS INCOMPLETO';
            $descripcion = 'No se puede emitir formulario, por falta de pagos de patentes:';
            $descripcion.= '<table><tr><td><b>Nro Inscripcion:</b></td>  <td> ' . $datosConcesion->numero_formulario . '</td></tr>';
            $descripcion.= '<tr><td><b>Nombre Concesion:</b></td>  <td> ' . $datosConcesion->nombre_concesion . '</td></tr>';
            $descripcion.= '<tr><td><b>Gestion que se emitio la resolucion:</b></td>  <td> ' . $gestionResolucion . '</td></tr>';
            $descripcion.= '<tr><td> <b>Gestion actual: </b></td>  <td>' . $datosImporte->gestion . '</td></tr></table>';
            $error = 'Patentes SIN PAGAR: <br /><br /> GESTION ' . $gestionesNoPagadas;

            $retornar->output = $linkVolver . ' ' . mensaje_error($titulo, $error, $descripcion);
            $this->_vista_principal($retornar);
        } else {
            ///////////////////////////////////////////////////////////////////////
            //-- 2. Controla si le corresponde pago PROGRESIVO
            ///////////////////////////////////////////////////////////////////////
            $cantidadAsignada = $datosConcesion->cantidad_asignada;
            switch (strtolower($datosConcesion->tipo_concesion)) {
                case 'cuadricula':
                    if ($datosConcesion->unidad === 'HAS')
                        $cantidadAsignada = $cantidadAsignada / 25; //convierte a cuadiculas si son HAS
                    $gestionesVigentes = $datosImporte->gestion - $gestionResolucion;    //saca el numero de gestiones de vigencia
                    $gestionesVigentes = 1 + $gestionesVigentes;                        // sumamos mas uno, para tomar en cuenta la gestion de la resolucion
                    if ($gestionesVigentes >= $datosImporte->gestiones_aplicables_progesivo) {
                        //--Define el costo de importe PROGRESIVO para CUADRICULA
                        $progresivo = TRUE;
                        $importe = $datosImporte->importe_cuadricula_progresivo;
                    } else {
                        //--Define el costo NORMAL para CUADRICULA
                        $progresivo = FALSE;
                        $importe = $datosImporte->importe_cuadricula;
                    }
                    break;
                case 'pertenencia':
                    if ($datosConcesion->unidad === 'M2')
                        $cantidadAsignada = $cantidadAsignada / 10000; //convierte a HAS si son M2
                    if ($cantidadAsignada > 1000) {
                        //--Define el costo de importe PROGRESIVO para CUADRICULA
                        $progresivo = TRUE;
                        $importe = $datosImporte->importe_pertenencia_progresivo;
                    } else {
                        //--Define el costo NORMAL para CUADRICULA
                        $progresivo = FALSE;
                        $importe = $datosImporte->importe_pertenencia;
                    }
                    break;
            }
            //-- Saca el nombre de concesion
            if ($datosConcesion->nombre_empresa == NULL OR $datosConcesion->nombre_empresa == '')
                $nombreConcesionario = $datosConcesion->nombre_persona . ' ' . $datosConcesion->paterno_persona . ' ' . $datosConcesion->materno_persona;
            else
                $nombreConcesionario = $datosConcesion->nombre_empresa;

            // prepara datos para enviar al formulario
            $datosFormulario['cantidadAsignada'] = $datosConcesion->cantidad_asignada;
            $datosFormulario['nombreConcesionario'] = $nombreConcesionario;
            $datosFormulario['nombreConcesion'] = $datosConcesion->nombre_concesion;
            $datosFormulario['numeroInscripcion'] = $datosConcesion->numero_formulario;
            $datosFormulario['padronNacional'] = $datosConcesion->padron_nacional;
            $datosFormulario['departamento'] = $datosConcesion->departamento;
            $datosFormulario['provincia'] = $datosConcesion->provincia;
            $datosFormulario['canton'] = $datosConcesion->canton;
            $datosFormulario['codigo_municipio'] = $datosConcesion->codigo_municipio;
            $datosFormulario['tipoConcesion'] = $datosConcesion->tipo_concesion;
            $datosFormulario['gestion'] = $datosImporte->gestion;
            $datosFormulario['unidad'] = $datosConcesion->unidad;
            $datosFormulario['progresivo'] = $progresivo;
            $datosFormulario['importe'] = $importe;
            $datosFormulario['importeTotal'] = $importe * $cantidadAsignada;
            $datosFormulario['nit'] = '---';
            $datosFormulario['telefono'] = '---';
            $datosFormulario['resolucion'] = $datosConcesion->numero_resolucion . ' de fecha ' . fecha_literal($datosConcesion->fecha_resolucion, 4);
            $datosFormulario['canton2'] = $datosConcesion->CANTON2;

            $datosInsertar = array('id_concesion_minera' => $id_concesion_minera,
                'importe_gestion' => $datosImporte->gestion,
                'importe' => $datosFormulario['importeTotal'],
                'id_importe_patente' => $datosImporte->id_importe_patente,
                'estado_formulario_pago_patente' => 'EMITIDO',
                'fecha_formulario_pago_patente' => date('Y-m-d H:i:s')
            );
            $this->db->insert('patentes', $datosInsertar); //inserta los datos en la tabla patentes para generar numero de formulario

            $datosFormulario['nroFormularioPagoPatente'] = 'FP' . $id_patentes = $this->db->insert_id(); //recupera el id_patente ingresado
            $datosFormulario['fechaEmision'] = date('d/m/Y');
            $datosFormulario['codigoBarras'] = $datosFormulario['nroFormularioPagoPatente'];

            //-- Genera codigo de barras
            //$this->load->helper(array('barcode39'));
            //$datosFormulario['codigoBarras']=barcode39_2($datosFormulario['nroFormularioPagoPatente']);
            // GENERA PDF
            //$this->load->helper(array('dompdf', 'file'));
            // page info here, db calls, etc.
            //$html = $this->load->view('formulario_pago_patentes.php', $datosFormulario, true);
            //pdf_create($html, 'filename');

            $datos['output'] = $this->load->view('formulario_pago_patentes.php', $datosFormulario, true);
            $this->_vista_principal($datos);
        }
    }

//=============================================================================================================
// FUNCIONES DE callback_column 
//=============================================================================================================
    function _concesion($value, $row) {
        $html = '<div class="message error">';
        if (strtolower($row->estado_concesion) == 'vigente')
            $html = '<div class="message success">';

        $html.= 'Concesion: <b>' . strtoupper($row->nombre_concesion) . '</b><br />';
        $html.= 'Fecha resolucion : <b>' . substr($row->fecha_resolucion, 0, 10) . '</b><br />';
        $html.= 'Tipo : <b>' . strtoupper($row->tipo_concesion) . '</b><br />';
        $html.= 'Cantidad Asignada : <b>' . strtoupper($row->cantidad_asignada) . ' ' . $row->unidad . '</b><br />';
        $html.= 'Departamento : <b>' . strtoupper($row->departamento) . '</b><br />';
        $html.= 'Provincia : <b>' . strtoupper($row->provincia) . '</b><br />';
        $html.= 'Canton/Municipio : <b>' . strtoupper($row->canton) . '</b><br />';
        $html.= 'Codigo Municipio : <b>' . strtoupper($row->codigo_municipio) . '</b><br />';
        $html.= '<p><strong>Estado : ' . strtoupper($row->estado_concesion) . '</strong></p></div>';
        return $html;
    }

    function _concesionario($value, $row) {
        $html = '';
        if ($row->nombre_empresa == NULL OR $row->nombre_empresa == '') {
            $html = 'Tipo : <b>Personal</b><br />';
            $html.='Nombre : <b>' . $row->nombre_persona . '</b><br />';
            $html.='Paterno : <b>' . $row->paterno_persona . '</b><br />';
            $html.='Materno : <b>' . $row->materno_persona . '</b><br />';
            $html.='CI : <b>' . $row->numero_identidad . '</b>';
        } else {
            $html = 'Tipo : <b>Empresa</b><br />';
            $html.='Nombre : <b>' . $row->nombre_empresa . '</b>';
        }
        return $html;
    }

    function _linkImprimir($value, $row) {
        $html = '';
        if (empty($row->fecha_resolucion) && strtolower($row->tipo_concesion) === 'cuadricula') {
            $html = '<div class="message error">No se puede emitir el formulario:<p><strong>No tiene FECHA DE RESOLUCION </strong></p></div>';
        } elseif (strtolower($row->estado_concesion) == 'vigente') {
            $html = '<a href="' . site_url('patente_regional/patentes_formularioDePagoDePatentes/' . $row->id_concesion_minera) . ' ">
                        <center><img src="' . base_url('estilo/images/imprimir.png') . '" title="Imprimir formulario de pago de patentes" alt="Imprimir formulario pago de patentes"/>
                        </center>
                     </a>';
            $html.= '<a href="' . site_url('patente_regional/patentes_controlarPagoDePatentes/' . $row->id_concesion_minera) . ' ">
                        <center> Realizar pago
                        </center>
                     </a>';
        }

        return $html;
    }

    function _controlaSiTienePagoPatentesAlDia($id_concesion_minera, $gestion_resolucion, $gestionImporteActual) {
        $html = '';
        //-- Lista las patentes pagadas de una concesion
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $patentes = $this->db->get('patentes');
        ///////////////////////////////////////////////////////////////////////
        //-- 1. Controla si una concesion tiene todas sus patentes canceladas
        ///////////////////////////////////////////////////////////////////////
        $gestion = 0;
        $gestionesPagadas = '';
        $gestionesNoPagadas = '';
        for ($gestion = $gestionResolucion; $gestion < $gestionImporteActual; $gestion++) {
            $sw = 0;
            foreach ($patentes->result() as $row) {
                $importeGestion = $row->importe_gestion;
                if ($gestion == $importeGestion)
                    $sw = 1;
            }
            if ($sw == 1)
                $gestionesPagadas.= '<br />' . $gestion;
            else
                $gestionesNoPagadas.= '<br />' . $gestion;
        }
        $html = 'gestion Resolucion: ' . $gestionResolucion;
        $html.= '<br />gestion actual: ' . $gestionActual;
        if (!($gestionesNoPagadas == '')) {
            $html.= '<br />Patente pagada gestion: ' . $gestionesPagadas;
            $html.= '<br />Patente SIN gestion: ' . $gestionesNoPagadas;
        } else {

            return $html;
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    //- Cambiar Contraseña ////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
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

}