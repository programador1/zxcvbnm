<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Patente_central extends CI_Controller {

    protected $htmlReporte = NULL;

    function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->library('session');
        $this->load->helper('sergeotecmin');
        $this->load->model('modelo_patente_central', '', TRUE);
    }

    function _vista_principal($output = null) {
        $this->load->view('index_patenteCentral.php', $output);
    }

    function index() {
        $this->_vista_principal((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    function concesion() {

        $crud = new grocery_CRUD();
        $crud->set_table('vista_concesion_minera');
        $crud->set_primary_key('id_concesion_minera', 'vista_concesion_minera');
        $crud->set_subject('Patentes');
        $crud->columns('numero_formulario', 'padron_nacional', 'nombre_concesion', 'concesionario');
        $crud->callback_column('nombre_concesion', array($this, '_concesion'));
        $crud->callback_column('concesionario', array($this, '_concesionario'));
        $crud->display_as('numero_formulario', 'Nro Formulario Inscripcion')
                ->display_as('padron_nacional', 'Nro Padron Nacional')
                ->display_as('nombre_concesion', 'Concesion Minera');
        $crud->add_action('Ver Pago de patentes', '', 'patente_central/patentes', 'edit-icon');
        $crud->unset_add()
             ->unset_edit()
             ->unset_delete()
             ->unset_print()
             ->unset_export();

        $output = $crud->render();
        $output->titulo = 'BUSCAR CONCESIONES MINERAS';
        $this->_vista_principal($output);
    }

    function patentes($id_concesion_minera) {
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $datosConcesionMinera = $this->db->get('concesion_minera')->row();

        $crud = new grocery_CRUD();
        $crud->where('id_concesion_minera', $id_concesion_minera);
        $crud->where('estado_formulario_pago_patente', 'PAGADO');
        $crud->order_by('fecha_registro_sistema', 'desc');
        $crud->set_table('patentes');
        $crud->set_primary_key('id_patentes', 'patentes');
        $crud->set_subject('Patentes');
        $crud->set_primary_key('id_concesion_minera', 'concesion_minera');
        //$crud->set_relation('id_concesion_minera', 'concesion_minera', 'numero_formulario');

        $crud->columns('importe_gestion','nro_formulario_pago_patente', 'importe', 'fecha_pago', 'fecha_abono', 'banco', 'lugar_pago', 'observaciones');        
        $crud->display_as('importe_gestion', 'Gestion')
             ->display_as('nro_formulario_pago_patente', 'Nro Boleta');
        
        $crud->fields('id_concesion_minera','importe_gestion', 'importe', 'nro_formulario_pago_patente', 'banco', 'fecha_pago', 'fecha_abono','lugar_pago', 'observaciones','fecha_registro_sistema', 'estado_formulario_pago_patente');
        $crud->required_fields('importe_gestion', 'importe', 'nro_formulario_pago_patente', 'banco', 'fecha_pago', 'fecha_abono','lugar_pago');
        $crud->set_rules('importe','Importe','numeric')
             ->set_rules('nro_formulario_pago_patente','Nro de Boleta','integer');
        $crud->field_type('fecha_pago', 'date')
             ->field_type('fecha_abono', 'date')             
             ->field_type('observaciones', 'text')             
             ->field_type('lugar_pago', 'enum',ARRAY('LA PAZ','SUCRE','COCHABAMBA','POTOSI','TARIJA','SANTA CRUZ','ORURO','TUPIZA'))
             ->field_type('importe_gestion', 'enum',array('2011','2012','2013','2014','2015','2016','2017','2018','S/G','2010','2009','2008','2007','2006','2005','2004','2003','2002','2001','2000','1999','1998','1997','1996'))
             ->field_type('banco', 'hidden','BM')
             ->field_type('fecha_registro_sistema', 'hidden',date('Y-m-d h:m:s'))
             ->field_type('id_concesion_minera', 'hidden',$id_concesion_minera)
             ->field_type('estado_formulario_pago_patente', 'hidden','PAGADO');
        $crud->unset_delete();

        $output = $crud->render();
        //$output->datosAdicionalesSuperior = $this->load->view('informacion_concesion_minera.php', $datosConcesionMinera, TRUE);
        //$output->titulo = 'PATENTES MINERAS';
        //-- Enivia la s vistas en forma de pestaña
        $enviarContenido['contenido']=array(    'Datos de Patentes'=>$this->load->view('vista_grocerycrud.php', $output, TRUE),
                                                'Datos de Concesion'=>$this->load->view('informacion_concesion_minera.php', $datosConcesionMinera, TRUE),
                                                'Datos Para Reporte'=>$this->patentes_reportes($id_concesion_minera));
        $output->output = boton('volver', site_url('patente_central/concesion')).$this->load->view('vista_pestana.php',$enviarContenido, TRUE);
        $output->titulo = 'PATENTES MINERAS';
        $this->_vista_principal($output);
    }
    function patentes_reportes($id_concesion_minera) {
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $datosConcesionMinera = $this->db->get('concesion_minera')->row();
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $this->db->order_by('importe_gestion','desc');
        $datosPatentes = $this->db->get('patentes');
        $html = mensaje_error('PATENTES SIN REGISTROS','Esta concesion, no tiene pago de patentes registrados!');
        if ($datosPatentes->num_rows() > 0) {            
            $this->load->library('table');
            $this->table->set_heading('NRO INCRIPCION','NOMBRE DE LA CONCESION', 'NOMBRE DEL CONCESIONARIO', 'CUAD', 'GESTION', 'BOLETA', 'MONTO', 'FECHA PAGO');
            foreach ($datosPatentes->result() AS $row){
                $this->table->add_row($datosConcesionMinera->numero_formulario,
                                        $datosConcesionMinera->nombre_concesion,
                                        $datosConcesionMinera->nombre_empresa==NULL?$datosConcesionMinera->nombre_persona.' '.$datosConcesionMinera->paterno_persona.' '.$datosConcesionMinera->materno_persona:$datosConcesionMinera->nombre_empresa,
                                        $datosConcesionMinera->cantidad_asignada,
                                        $row->importe_gestion,
                                        $row->nro_formulario_pago_patente,
                                        alinear(number_format($row->importe,2),'derecha'),
                                        date('d/m/Y',  strtotime($row->fecha_pago))
                                      );
            }            
            
            $cabecera= '<br /> DATOS PARA REPORTE DE PAGO DE PATENTES';
            $cabecera.= '<br />';
            $html = $cabecera.$this->table->generate();                
            }
            return ($html);

    }
    function patentes_formularioDePagoDePatentes($id_concesion_minera) {
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $query = $this->db->get('concesion_minera')->row();
        $output['datos'] = $query;
        $this->load->view('formulario_pago_patenrtes.php', $output);
    }

// FUNCIONES DE callback_column -------------------------------------------------------------------------	
    function _concesion($value, $row) {
        $html = '<div class="message error">';
        if (strtolower($row->estado_concesion) == 'vigente')
            $html = '<div class="message success">';

        $html.= 'Concesion: <b>' . strtoupper($row->nombre_concesion) . '</b><br />';
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
        /*$html = '';
        if ($row->nombre_empresa == NULL OR $row->nombre_empresa == '') {
            $html = 'Tipo : <b>Personal</b><br />';
            $html.='Nombre : <b>' . $row->nombre_persona . '</b><br />';
            $html.='Paterno : <b>' . $row->paterno_persona . '</b><br />';
            $html.='Materno : <b>' . $row->materno_persona . '</b><br />';
            $html.='CI : <b>' . $row->numero_identidad . '</b>';
        } else {
            $html = 'Tipo : <b>Empresa</b><br />';
            $html.='Nombre : <b>' . $row->nombre_empresa . '</b>';
        }*/
        return $row->concesionario;
    }

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//-- Modulo: Insertar Formulario de pago de patentes -------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    function formularioPagoPatente($datos='') {
        $datos['combo_banco'] = combo_pagoPatente_banco($this->session->userdata('pagoPatenteBanco'));
        $datos['fechaAbono'] = $this->session->userdata('fechaAbono');

        $datos['output'] = $this->load->view('patenteCentral_buscar_FormularioPagoPatente.php', $datos, TRUE);
        $this->_vista_principal($datos);
    }

    function buscar_formularioPagoPatente() {
        $this->session->set_userdata('pagoPatenteBanco', $this->input->post('pagoPatente_banco'));
        $this->session->set_userdata('fechaAbono', $this->input->post('fechaAbono'));
        $nroFormularioPagoPatente = $this->input->post('nroFormularioPagoPatente');
        $id_patentes = substr($nroFormularioPagoPatente, 2);

        $this->db->where('id_patentes', $id_patentes);
        $this->db->where('nro_formulario_pago_patente', $nroFormularioPagoPatente);
        $query = $this->db->get('patentes');
        if ($query->num_rows() > 0) {
            $datosPatente = $query->row();
            $id_patentes = $datosPatente->id_patentes;
            //--Verifica si la boleta a sido ya ingresada al sistema
            $this->db->where('id_patentes', $id_patentes);
            $this->db->where('estado_formulario_pago_patente', 'PAGADO');
            $this->db->where('nro_formulario_pago_patente', $nroFormularioPagoPatente);
            $consulta = $this->db->get('patentes');
            if ($consulta->num_rows() > 0) {
                $titulo = 'El NUMERO DE FORMULARIO YA SE ENCUENTRA REGISTRADO';
                $error = 'EL Nro de formulario: ' . $nroFormularioPagoPatente . ' ya se encuentra registrado en la base de datos';
                $enviar_datos['mensaje_error'] = mensaje_error($titulo, $error);            
                $this->formularioPagoPatente($enviar_datos);                
            }else{
                //--Verifica si se esta pagando 2 veces la misma gestion
                $this->db->where('id_concesion_minera', $datosPatente->id_concesion_minera);
                $this->db->where('importe_gestion', $datosPatente->importe_gestion);
                $this->db->where('estado_formulario_pago_patente', 'PAGADO');
                $consulta2 = $this->db->get('patentes');
                if ($consulta2->num_rows() > 0) {
                $titulo = 'LA GESTION DE QUE DESEA CANCELAR YA SE ENCUENTRA REGISTRADO EN EL SISTEMA';
                $error = 'La gestion: ' . $datosPatente->importe_gestion . ' del nro de formulario '.$nroFormularioPagoPatente.' que pretende pagar ya se encuentra pagado';
                $enviar_datos['mensaje_error'] = mensaje_error($titulo, $error);            
                $this->formularioPagoPatente($enviar_datos);                
                }else{
                    //--Verifica si la gestion ya esta cancelada            
                    redirect(strtolower('Patente_central/editar_formularioPagoPatente/edit/') . $id_patentes);
                }               
            }
            //--Verifica si la gestion ya esta cancelada            
            //redirect(strtolower('Patente_central/editar_formularioPagoPatente/edit/') . $id_patentes);
        } else {
            $titulo = 'El NUMERO DE FORMULARIO DE PAGO DE PATENTES NO EXISTE';
            $error = 'EL Nro de formulario: ' . $nroFormularioPagoPatente . ' no se encuentra registrado en la base de datos';
            $enviar_datos['mensaje_error'] = mensaje_error($titulo, $error);            
            $this->formularioPagoPatente($enviar_datos);
        }
    }

    function editar_formularioPagoPatente() {

        $crud = new grocery_CRUD();
        $crud->set_table('patentes');
        $crud->set_primary_key('id_patentes', 'patentes');
        $crud->set_subject('Formulario Pago de Patentes');
        //$crud->edit_fields('id_concesion_minera', 'importe_gestion',  'nro_formulario_pago_patente', 'fecha_formulario_pago_patente',  'importe',  'fecha_pago',  'banco',  'observaciones',  'fecha_registro_sistema',  'fecha_abono', 'id_importe_patente',  'estado_formulario_pago_patente');
        $crud->edit_fields('fecha_abono', 'banco', 'fecha_pago', 'observaciones', 'fecha_registro_sistema', 'estado_formulario_pago_patente');

        $crud->field_type('fecha_abono', 'hidden', $this->session->userdata('fechaAbono'));
        $crud->field_type('banco', 'hidden', $this->session->userdata('pagoPatenteBanco'));
        $crud->field_type('fecha_registro_sistema', 'hidden', date('Y-m-d H:i:s'));
        $crud->field_type('estado_formulario_pago_patente', 'hidden', 'PAGADO');
        $crud->field_type('fecha_pago', 'date');
        $crud->required_fields('fecha_pago');
        $crud->field_type('fruits', 'set', array('banana', 'orange', 'apple', 'lemon'));

        $crud->unset_list();
        $crud->unset_back_to_list();
        $crud->set_lang_string('update_success_message', 'Sus datos an sido correctamente Almacenados en la Base de datos.<br/> 
                   <h3><a href="' . site_url('patente_central/formularioPagoPatente') . '">Realizar nueva busqueda</a>  </h3>
                 
                 <div style="display:none">
                 '
        );

        $output = $crud->render();
        $output->datosAdicionalesSuperior = $this->_formularioDePagoDePatentes($this->uri->segment('4'));
        $this->_vista_principal($output);
    }

    function _formularioDePagoDePatentes($id_patente) {
        //-- Saca los datos de importe gestion vigente
        $this->db->where('id_patentes', $id_patente);
        $datosPatente = $this->db->get('patentes')->row();
        //-- Saca los datos de una concesion
        $this->db->where('id_concesion_minera', $datosPatente->id_concesion_minera);
        $datosConcesion = $this->db->get('concesion_minera')->row();
        //-- Saca los datos de una concesion
        $this->db->where('id_importe_patente', $datosPatente->id_importe_patente);
        $datosImportePatente = $this->db->get('importe_patente')->row();

        //-- recupera datos que se usaran para controlar
        if (strtolower($datosConcesion->tipo_concesion) === 'cuadricula')
            $gestionResolucion = substr($datosConcesion->fecha_resolucion, 0, 4);
        else
            $gestionResolucion = substr($datosConcesion->fecha_inscripcion, 0, 4);


        //-- Saca el nombre de concesion
        if ($datosConcesion->nombre_empresa == NULL OR $datosConcesion->nombre_empresa == '')
            $nombreConcesionario = $datosConcesion->nombre_persona . ' ' . $datosConcesion->paterno_persona . ' ' . $datosConcesion->materno_persona;
        else
            $nombreConcesionario = $datosConcesion->nombre_empresa;

        $cantidadAsignada = $datosConcesion->cantidad_asignada;
        switch (strtolower($datosConcesion->tipo_concesion)) {
            case 'cuadricula':
                $gestionesVigentes = $datosImportePatente->gestion - $gestionResolucion;    //saca el numero de gestiones de vigencia
                if ($gestionesVigentes >= $datosImportePatente->gestiones_aplicables_progesivo) {
                    //--Define si es PROGRESIVO para CUADRICULA
                    $progresivo = TRUE;
                    $importe = $datosImportePatente->importe_cuadricula_progresivo;
                } else {
                    //--Define si es NORMAL para CUADRICULA
                    $progresivo = FALSE;
                    $importe = $datosImportePatente->importe_cuadricula;
                }
                break;
            case 'pertenencia':
                if ($cantidadAsignada > 1000) {
                    //--Define si es PROGRESIVO para CUADRICULA
                    $progresivo = TRUE;
                    $importe = $datosImportePatente->importe_pertenencia_progresivo;
                } else {
                    //--Define si es NORMAL para CUADRICULA
                    $progresivo = FALSE;
                    $importe = $datosImportePatente->importe_pertenencia;
                }
                break;
        }
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
        $datosFormulario['gestion'] = $datosPatente->importe_gestion;
        $datosFormulario['unidad'] = $datosConcesion->unidad;
        $datosFormulario['progresivo'] = $progresivo;
        $datosFormulario['importe'] = $importe;
        $datosFormulario['importeTotal'] = $datosPatente->importe;
        $datosFormulario['nit'] = '---';
        $datosFormulario['telefono'] = '---';

        $datosFormulario['nroFormularioPagoPatente'] = $datosPatente->nro_formulario_pago_patente; //recupera el id_patente ingresado
        $datosFormulario['fechaEmision'] = $datosPatente->fecha_formulario_pago_patente;
        $datosFormulario['codigoBarras'] = '';

        $formularioPagoPatente = $this->load->view('formulario_pago_patentes.php', $datosFormulario, true);
        return($formularioPagoPatente);
    }

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//-- Modulo: R E P O R T E S -------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //-- Reporte Diario
    function reporte_diario($datos='') {
        $datos['titulo'] = 'REPORTE DIARIO';
        $datos['url'] = site_url('patente_central/listar_reporteDiario');
        $datos['reporte_diario'] = TRUE;
        $datos['output'] = $this->load->view('patenteCentral_buscar_reporteDiario.php', $datos, TRUE);
        $this->_vista_principal($datos);
    }

    function listar_reporteDiario() {
            $fechaAbono = $this->input->post('fechaInicio');
        $datosConsulta = $this->modelo_patente_central->reporte_diarioPatentes($fechaAbono);
        //var_dump ($datosConsulta); exit;
        
        if (!$datosConsulta) {
            $titulo='FECHA SIN PAGO DE PATENTES';
            $error='La fecha: ' . $fechaAbono . ' no tiene registrado ningun pago de patentes en la base de datos';
            $datos['mensaje_error'] = mensaje_error($titulo, $error);
            $this->reporte_diario($datos);
        } else {
            $this->load->library('table');
            $this->table->set_heading('Nro','Tipo', 'Estado', 'Nro Inscripci&oacute;n', 'Nombre Concesi&oacute;n', 'Gesti&oacute;n', 'Nro Boleta', 'Importe en Bs.', 'Fecha Pago');
            $nro=0;
            $totalRecaudado=0;
            foreach ($datosConsulta->result() AS $row){
                ++$nro;
                $totalRecaudado = $totalRecaudado + $row->importe;
                $this->table->add_row(alinear($nro,'derecha'),$row->tipo,$row->estado,alinear($row->nro_inscripcion,'derecha'),$row->nombre_concesion,alinear($row->gestion,'centro'),alinear($row->nro_boleta,'derecha'),alinear(number_format($row->importe,2),'derecha'),alinear($row->fecha_pago,'centro'));
            }            
            $cabecera = 'Nro total de registros: '.$nro;
            $cabecera.= '<br /> Monto total recaudado: '.number_format($totalRecaudado,2).' ['.  numero_letra($totalRecaudado).']';
            $cabecera.= '<br />';
            $pie = '<br /><br /><br /><br /><br />
                    <table border="0" style="width:70%; text-align:center;" align="center">                        
                        <tr>
                        <td style="border:none; padding:0px; border-spacing: 0"> Yola N. Sacaca Balboa </td>
                        <td style="border:none; padding:0px; border-spacing: 0"> Tec. Erik M. Cuaquira Mendoza </td>
                        </tr>
                        <tr>
                        <td style="border:none; padding:0px; border-spacing: 0">ENCARGADA PATENTES MINERAS</td>
                        <td style="border:none; padding:0px; border-spacing: 0">ENGARGADO INFORMATICA</td>
                        </tr>
                    </table>
                    cc. INFORMATICA
                    <br />SIN SELLO DE INFORMATICA NO TIENE VALIDEZ DE REVISION
                    ';
            $enviarDatos['reporte'] = $cabecera.$this->table->generate().$pie; 
            
            
            
            
            
            
            $enviarDatos['tipoReporte'] = 'REPORTE DIARIO DE PAGO DE PATENTES';
            $enviarDatos['tituloReporte'] = 'REPORTE DEL "'.fecha_literal($fechaAbono,'4').'"';            
            
                $datos['output'] = $this->load->view('vista_reportes.php', $enviarDatos, TRUE);
                $this->session->set_userdata('htmlReporte', $datos['output']);
                $this->_vista_principal($datos);
            }
    }
    

    //-- Reporte por Periodos
    function reporte_general($datos='') {
        $datos['titulo'] = 'REPORTE GENERAL';
        $datos['url'] = site_url('patente_central/listar_reporteGeneral');
        $datos['output'] = $this->load->view('patenteCentral_buscar_reporteDiario.php', $datos, TRUE);
        $this->_vista_principal($datos);
    }

    function listar_reporteGeneral() {
        
        $fechaInicio = $this->input->post('fechaInicio');
        $fechaFinal = $this->input->post('fechaFinal');
        $datosConsulta = $this->modelo_patente_central->reporte_generalPatentes($fechaInicio, $fechaFinal);
        
        if (!$datosConsulta) {
            $titulo='FECHA SIN PAGO DE PATENTES';
            $error='En la fecha: "' . $fechaInicio . '" al "'.$fechaFinal.'" no se tienen realizados ningun pago de patentes en la base de datos';
            $datos['mensaje_error'] = mensaje_error($titulo, $error);
            $this->reporte_general($datos);
        } else {
            $this->load->library('table');
            //$this->table->set_heading('Tipo', 'Estado', 'Nro Inscripcion', 'Nombre Concesion', 'Gestion', 'Nro Formulario', 'Fecha Pago', 'Fecha Abono', 'Regional');            
            $enviarDatos['tipoReporte'] = 'REPORTE GENERAL DE PAGO DE PATENTES';  
            $enviarDatos['tituloReporte'] = 'REPORTE DEL "'.fecha_literal($fechaInicio,'4').'" AL "'.fecha_literal($fechaFinal,'4').'"';
            //-- Genera la tabla
            $this->load->library('table');
            $this->table->set_heading('Fecha Pago','Nro Formularios Procesados','Monto Recaudado 100%','Comisi&oacute;n Bancaria 2%','Ingreso S/G Extracto Bancario 98%');
            $totalFormulariosProcesados=0;
            $totalMontoRecaudado=0;
            $totalComisionBancaria=0;
            $totalIngresoExtractoBancario=0;
            foreach ($datosConsulta->result() AS $row){
                $totalFormulariosProcesados = $totalFormulariosProcesados + $row->nro_formularios_procesados;
                $totalMontoRecaudado = $totalMontoRecaudado + $row->monto_recaudado;
                $totalComisionBancaria = $totalComisionBancaria + $row->comision_bancaria;
                $totalIngresoExtractoBancario = $totalIngresoExtractoBancario + $row->ingreso_extracto_bancario;
                    $this->table->add_row(alinear($row->fecha_pago,'centro'), alinear($row->nro_formularios_procesados,'derecha'),alinear(number_format($row->monto_recaudado,2),'derecha'), alinear(number_format($row->comision_bancaria,2),'derecha'), alinear(number_format($row->ingreso_extracto_bancario,2),'derecha'));
            }            
            $this->table->add_row(alinear('TOTALES','centro','negrita','14'), alinear(number_format($totalFormulariosProcesados),'derecha','negrita','14'),  alinear(number_format($totalMontoRecaudado,2),'derecha','negrita','14'), alinear(number_format($totalComisionBancaria,2),'derecha','negrita','14'), alinear(number_format($totalIngresoExtractoBancario,2),'derecha','negrita','14'));
            $enviarDatos['reporte'] = $this->table->generate(); 
            
                $datos['output'] = $this->load->view('vista_reportes.php', $enviarDatos, TRUE);
                $this->session->set_userdata('htmlReporte', $datos['output']);
                $this->_vista_principal($datos);
            }        
    }

    
    
    //**********************************************************************************************************
    //-- Verifica las concesion POR CUADRICULAS que no tengan completo sus pagos de patentes ***********************************
    //**********************************************************************************************************
    function verificar_concesinesConPagosIncompletosDePatentes() {
        $datosConcesionesVigentes = $this->modelo_patente_central->concesiones_vigentes_con_fechaResolucion();
        if (!$datosConcesionesVigentes) {
            echo 'No Se encontraron datos de concesiones vigentes con fecha de resolucion';
            exit;
        } else {
            $nro=0;
            $this->load->library('table');
            $this->table->set_heading('Nro','Nro Inscripcion', 'Nombre concesion','fecha Resolucion','Numero Resolucion','Gestiones Pagadas', 'Gestiones No Pagadas' ,'Accion'
                    );

            $enviarDatos['tipoReporte'] = 'REPORTE PATENTES';
            $enviarDatos['tituloReporte'] = 'CONCESIONES MINERAS POR CUADRICULA <br /> QUE NO TIENEN AL DIA SUS PAGOS DE PATENTES <br /><br />';
            
            foreach ($datosConcesionesVigentes->result() AS $concesion) {
                $gestionResolucion = substr($concesion->fecha_resolucion, 0, 4); 
                $id_concesion_minera = $concesion->id_concesion_minera;
                $datosPatentes = $this->modelo_patente_central->patentes_concesiones($id_concesion_minera);

                if ($datosPatentes) {
                    $gestionesPagadas = '';
                    $gestionesNoPagadas = '';
                    for ($gestion = $gestionResolucion; $gestion <= 2011; $gestion++) {
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
                    $this->table->add_row(++$nro,$concesion->numero_formulario, $concesion->nombre_concesion,  date('d-m-Y',  strtotime($concesion->fecha_resolucion)),$concesion->numero_resolucion,$gestionesPagadas , $gestionesNoPagadas
                                            ,'<a href="'. site_url('patente_central/patentes/'.$id_concesion_minera).'">Ver datos </a>'
                                );
                }else{
                    //echo $id_concesion_minera;exit;
                    $this->table->add_row(++$nro,$concesion->numero_formulario, $concesion->nombre_concesion, date('d-m-Y',  strtotime($concesion->fecha_resolucion)),$concesion->numero_resolucion,'-' , 'No tiene ningun pago registrado en la base de datos'
                                    ,'<a href="'. site_url('patente_central/patentes/'.$id_concesion_minera).'">Ver datos </a>'
                                );
                }
            }

            $enviarDatos['reporte'] = $this->table->generate(); //tabla con resultado de reporte diario
            $datos['output'] = $this->load->view('vista_reportes.php', $enviarDatos, TRUE);
            $this->_vista_principal($datos);
        }

    }
//**********************************************************************************************************
    //-- Verifica las concesion POR PERTENENCIA que no tengan completo sus pagos de patentes ***********************************
    //**********************************************************************************************************
    function verificar_concesinesConPagosIncompletosDePatentesPertenencia() {
        $datosConcesionesVigentes = $this->modelo_patente_central->concesiones_vigentes_con_fechaInscripcion();
        if (!$datosConcesionesVigentes) {
            echo 'No Se encontraron datos de concesiones vigentes con fecha de resolucion';
            exit;
        } else {
            $nro=0;
            $this->load->library('table');
            $this->table->set_heading('Nro','Nro Inscripcion', 'Nombre concesion','Concesionario','Nacionalizada','fecha Inscripcion','Gestiones Pagadas', 'Gestiones No Pagadas' ,'Accion');

            $enviarDatos['tipoReporte'] = 'REPORTE PATENTES';
            $enviarDatos['tituloReporte'] = 'CONCESIONES MINERAS POR PERTENENCIA <br /> QUE NO TIENEN AL DIA SUS PAGOS DE PATENTES <br /><br />';
            
            foreach ($datosConcesionesVigentes->result() AS $concesion) {
                $gestionResolucion = substr($concesion->fecha_inscripcion, 0, 4); 
                $id_concesion_minera = $concesion->id_concesion_minera;
                $datosPatentes = $this->modelo_patente_central->patentes_concesiones($id_concesion_minera);

                if ($datosPatentes) {
                    $gestionesPagadas = '';
                    $gestionesNoPagadas = '';
                    for ($gestion = $gestionResolucion; $gestion <= 2011; $gestion++) {
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
                    $this->table->add_row(++$nro,$concesion->numero_formulario, $concesion->nombre_concesion,  
                                            $concesion->nombre_empresa==NULL?$concesion->nombre_persona.' '.$concesion->paterno_persona.' '.$concesion->materno_persona:$concesion->nombre_empresa,
                                            $concesion->NACIONALIZADA=='t'?'SI':'NO',
                                            date('d-m-Y',  strtotime($concesion->fecha_inscripcion)),$gestionesPagadas , $gestionesNoPagadas
                                            ,'<a href="'. site_url('patente_central/patentes/'.$id_concesion_minera).'">Ver datos </a>'
                                );
                }else{
                    //echo $id_concesion_minera;exit;
                    $this->table->add_row(++$nro,$concesion->numero_formulario, $concesion->nombre_concesion, 
                                    $concesion->nombre_empresa,
                                    $concesion->NACIONALIZADA=='t'?'SI':'NO',
                                    date('d-m-Y',  strtotime($concesion->fecha_inscripcion)),'-' , 'No tiene ningun pago registrado en la base de datos'
                                    ,'<a href="'. site_url('patente_central/patentes/'.$id_concesion_minera).'">Ver datos </a>'
                                );
                }
            }

            $enviarDatos['reporte'] = $this->table->generate(); //tabla con resultado de reporte diario
            $datos['output'] = $this->load->view('vista_reportes.php', $enviarDatos, TRUE);
            $this->_vista_principal($datos);
        }

    }
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//-- FUNCIONES DE USO GENERAL ------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    function imprimir_pdf() {
        // GENERA PDF
        $html = $this->session->userdata('htmlReporte');
        $this->session->unset_userdata('htmlReporte', '');
        $this->load->helper(array('dompdf', 'file'));
        // page info here, db calls, etc.
        //$html = $this->load->view('formulario_pago_patentes.php', $output, true);
        //pdf_create($html, 'sergeotecmin');
        echo $html;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    //- Cambiar Contraseña ////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    function usuario(){
        $this->load->library('funciones_comunes');
        $usuario = new Funciones_comunes();
        $output= $usuario->usuario_cambiarPassword();
        $this->_vista_principal($output);
    }
    
    function mensaje(){
        $this->load->library('funciones_comunes');
        $mensaje = new Funciones_comunes();
        $output= $mensaje->mensaje();
        $this->_vista_principal($output);
    }
}