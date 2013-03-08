<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administrativo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->library('session');
        $this->load->model('modelo_administrador', '', TRUE);
        $this->load->helper('sergeotecmin');
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
        $crud->callback_column('nombre_concesion', array($this, '_concesion'))
             ->callback_column('concesionario', array($this, '_concesionario'));
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

        $crud->columns('importe_gestion', 'importe', 'nro_formulario_pago_patente', 'banco', 'fecha_pago', 'fecha_abono', 'observaciones');
        $crud->fields('importe_gestion', 'importe', 'nro_formulario_pago_patente', 'banco', 'fecha_pago', 'fecha_abono', 'observaciones');
        $crud->required_fields('importe_gestion', 'importe', 'nro_formulario_pago_patente', 'banco', 'fecha_pago', 'fecha_abono');
        $crud->display_as('importe_gestion', 'Gestion')
             ->display_as('nro_formulario_pago_patente', 'Nro Boleta');
        $crud->field_type('fecha_pago', 'date')
             ->field_type('fecha_abono', 'date');
        $crud->unset_operations();

        $output = $crud->render();
        //-- Enivia la s vistas en forma de pestaña
        $enviarContenido['contenido']=array('Datos de Concesion'=>$this->load->view('informacion_concesion_minera.php', $datosConcesionMinera, TRUE),
                                            'Datos de Patentes'=>$this->load->view('vista_grocerycrud.php', $output, TRUE));        
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