<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administrador extends CI_Controller {

    function __construct() {
        parent::__construct();        
        $this->load->model('modelo_administrador', '', TRUE);
    }

    function _vista_principal($output = null) {
        $this->load->view('index_administrador.php', $output);
    }
    
    function index() {
        $this->_vista_principal((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

    function usuario() {
        $crud = new grocery_CRUD();
        $crud->set_table('usuario');
        $crud->set_primary_key('id_usuario', 'usuario');
        $crud->set_subject('Usuario');

        $crud->set_primary_key('id_regional', 'regional');
        $crud->set_relation('id_regional', 'regional', 'nombre_regional');
        $crud->fields('id_regional', 'nombre', 'primer_apellido', 'segundo_apellido', 'usuario_nombre', 'usuario_password', 'usuario_tipo', 'fecha_alta', 'fecha_baja', 'estado');
        $crud->columns('id_regional', 'nombre', 'usuario_nombre', 'usuario_password', 'usuario_tipo', 'fecha_alta', 'fecha_baja', 'estado');
        $crud->callback_column('nombre', array($this, '_usuario_nombre'));
        $crud->field_type('usuario_tipo', 'enum', tipo_usuario())
             ->field_type('estado', 'true_false')
             ->field_type('usuario_password', 'password');
        $crud->display_as('id_regional','Regional')
             ->display_as('usuario_tipo','Tipo de Usuario')
             ->display_as('usuario_nombre','Nombre de Usuario')
             ->display_as('usuario_password','Password de Usuario');
        $crud->required_fields('id_regional', 'nombre', 'primer_apellido', 'usuario_nombre', 'usuario_password', 'usuario_tipo', 'fecha_alta', 'estado');

        $output = $crud->render();
        $this->_vista_principal($output);
    }

    function importe_patente() {
        $crud = new grocery_CRUD();
        $crud->set_table('importe_patente');
        $crud->set_primary_key('id_importe_patente', 'importe_patente');
        $crud->set_subject('Importe Patente por Gestion');

        $crud->columns('gestion', 'importe_pertenencia', 'importe_pertenencia_progresivo', 'importe_cuadricula', 'importe_cuadricula_progresivo', 'gestiones_aplicables_progesivo', 'fecha_inicio_pago', 'fecha_final_pago', 'estado');
        $crud->fields('gestion', 'importe_pertenencia', 'importe_pertenencia_progresivo', 'importe_cuadricula', 'importe_cuadricula_progresivo', 'gestiones_aplicables_progesivo', 'fecha_inicio_pago', 'fecha_final_pago', 'estado');
        $crud->field_type('estado', 'true_false');
        $output = $crud->render();
        $this->_vista_principal($output);
    }

    function regional() {
        $crud = new grocery_CRUD();
        $crud->set_table('regional');
        $crud->set_primary_key('id_regional', 'regional');
        $crud->set_subject('Regional');

        $crud->columns('nombre_regional');
        $crud->fields('nombre_regional');
        $output = $crud->render();
        $this->_vista_principal($output);
    }
    function _usuario_nombre($valor, $row) {
        $html = $row->nombre . ' ' . $row->primer_apellido . ' ' . $row->segundo_apellido;
        return $html;
    }
//------------------------------------------------------------------
    function corregir_nombreEmpresa() {
        $crud = new grocery_CRUD();
        $crud->where('nombre_empresa IS NOT NULL','',FALSE);
        $crud->set_table('concesion_minera');
        $crud->set_primary_key('id_concesion_minera', 'concesion_minera');
        $crud->set_subject('Nombre Empresa');

        $crud->columns('nombre_empresa');
        $crud->fields('nombre_empresa');
        $output = $crud->render();
        $this->_vista_principal($output);
    }
    
    

    //**********************************************************************************************************
    //-- Verifica las concesion que no tengan completo sus pados de patentes ***********************************
    //**********************************************************************************************************
    function verificar_concesinesConPagosIncompletosDePatentes() {
        $datosConcesionesVigentes = $this->modelo_administrador->concesiones_vigentes_con_fechaResolucion();
        if (!$datosConcesionesVigentes) {
            echo 'No Se encontraron datos de concesiones vigentes con fecha de resolucion';
            exit;
        } else {
            $nro=0;
            $this->load->library('table');
            $this->table->set_heading('Nro','Nro Inscripcion', 'fecha Resolucion','Gestiones Pagadas', 'Gestiones No Pagadas');

            $enviarDatos['tipoReporte'] = 'REPORTE PATENTES';
            $enviarDatos['tituloReporte'] = 'CONCESIONES POR CUADRICULA QUE NO TIENEN AL DIA SUS PAGOS DE PATENTES';

            foreach ($datosConcesionesVigentes->result() AS $concesion) {
                $gestionResolucion = substr($concesion->fecha_resolucion, 0, 4);
                $id_concesion_minera = $concesion->id_concesion_minera;
                $datosPatentes = $this->modelo_administrador->patentes_concesiones($id_concesion_minera);

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
                    $this->table->add_row(++$nro,$concesion->numero_formulario, $concesion->fecha_resolucion,$gestionesPagadas , $gestionesNoPagadas);
                }
            }

            $enviarDatos['reporte'] = $this->table->generate(); //tabla con resultado de reporte diario
            $datos['output'] = $this->load->view('vista_reportes.php', $enviarDatos, TRUE);
            $this->_vista_principal($datos);
        }

    }
    
   
    function mensaje(){
        $this->load->library('funciones_comunes');
        $mensaje = new Funciones_comunes();
        $output= $mensaje->mensaje();
        $this->_vista_principal($output);
    }

}