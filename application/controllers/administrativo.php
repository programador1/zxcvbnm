<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administrativo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('funciones_comunes');
        $this->load->model('modelo_administrador', '', TRUE);
        
    }

    function _vista_principal($output = null) {
        $this->load->view('index_administrativo.php', $output);
    }
    
    function index() {
        $this->_vista_principal((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }
    //- Muestra la informacion de las concesiones
   function informacion_concesion() {

        $crud = new grocery_CRUD();
        $crud->set_table('vista_concesion_minera');
        $crud->set_primary_key('id_concesion_minera', 'vista_concesion_minera');
        $crud->set_subject('Patentes');
        $crud->columns('numero_formulario', 'padron_nacional', 'nombre_concesion', 'concesionario');
        $crud->callback_column('nombre_concesion', array($this, '_concesion'));
        $crud->display_as('numero_formulario', 'Nro Formulario Inscripcion')
             ->display_as('padron_nacional', 'Nro Padron Nacional')
             ->display_as('nombre_concesion', 'Concesion Minera');
        $crud->add_action('Ver mas Informacion', base_url('estilo/images/mas_informacion.png'), 'administrativo/informacion_patentes');
        $crud->unset_add()
             ->unset_edit()
             ->unset_delete()
             ->unset_print()
             ->unset_export();
        $output = $crud->render();
        $output->titulo = 'BUSCAR CONCESIONES MINERAS';
        $this->_vista_principal($output);
    }
    
    function informacion_patentes($id_concesion_minera) {        
        $funcionesComunes = new funciones_comunes();
        $enviarContenido['contenido']=array('Datos de Concesion'=>$funcionesComunes->informacion_concesionMinera($id_concesion_minera),
                                            'Datos de Patentes'=>$funcionesComunes->informacion_patentes($id_concesion_minera)
                                            );
        $output->output = boton('volver').$this->load->view('vista_pestana.php',$enviarContenido, TRUE);
        $output->titulo = 'BUSCAR CONCESIONES MINERAS';
        $this->_vista_principal($output);
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