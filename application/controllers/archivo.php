<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Archivo extends CI_Controller {

    function __construct() {
        parent::__construct();        
    }

    function _vista_principal($output = null) {
        $this->load->view('index_archivo.php', $output);
    }

    function index() {
        $this->_vista_principal((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

//DATOS DE CONCESION MINERA -------------------------------------------------------------------------------------------------------
    function buscar_concesionesMineras() {

        $crud = new grocery_CRUD();
        $crud->set_table('vista_concesion_minera');
        $crud->set_primary_key('id_concesion_minera', 'vista_concesion_minera');
        $crud->set_subject('Patentes');
        $crud->columns('numero_formulario', 'padron_nacional', 'nombre_concesion', 'concesionario');
        $crud->callback_column('nombre_concesion', array($this, '_concesion'));
        $crud->display_as('numero_formulario', 'Nro Formulario Inscripcion')
                ->display_as('padron_nacional', 'Nro Padron Nacional')
                ->display_as('nombre_concesion', 'Concesion Minera');
        $crud->add_action('Ver mas Informacion', base_url('estilo/images/mas_informacion.png'), 'archivo/ver_datosConcesionMinera');
        $crud->unset_add()
                ->unset_edit()
                ->unset_delete()
                ->unset_print()
                ->unset_export();

        $output = $crud->render();
        $output->titulo = 'BUSCAR CONCESIONES MINERAS';
        $this->_vista_principal($output);
    }

    function ver_datosConcesionMinera($id_concesion_minera) {
        //datos de etiqueta archivo
        $this->db->where('id_concesion_minera',$id_concesion_minera);
        $row=$this->db->get('concesion_minera')->row();
        $this->load->library('table');
        $this->table->set_heading('Regional','Nro Formulario','A&ntilde;o Resolucion/Gaceta','Concesion','Nro padron nacional');                
        $gestion='';
        if($row->fecha_resolucion <> NULL)  $gestion = substr($row->fecha_resolucion, 0, 4);
        else $gestion = substr($row->fecha_gaceta, 0, 4);
        $this->table->add_row($row->regional,$row->numero_formulario,$gestion,$row->nombre_concesion,'PADRON-'.$row->padron_nacional);
        $etiquetaArchivo = '<center><h2>ETIQUETA ARCHIVO</h2>'.$this->table->generate().'</center>';
        
        $this->load->library('funciones_comunes');
        $datosConcesionMinera = new Funciones_comunes();
        //- Envia las vistas en forma de pestaña
        $enviarContenido['contenido'] = array('Datos de Concesion' => $datosConcesionMinera->informacion_concesionMinera($id_concesion_minera),
                                                'Datos de Patentes' => $datosConcesionMinera->informacion_patentes($id_concesion_minera),
                                                'Etiqueta de Archivo' => $etiquetaArchivo
                                                );
        $encabezado=boton('volver');
        $output['output'] = $encabezado.$this->load->view('vista_pestana.php', $enviarContenido, TRUE);
        $output['titulo'] = 'INFORMACION CONCESION MINERA';
        $this->_vista_principal((object)$output);
    }

//CORREGIR DATOS DE FECHA RESOLUCION-------------------------------------------------------------------------------------------------------    
    function corregir_datos() {

        $crud = new grocery_CRUD();
        $crud->set_table('vista_concesion_minera');
        $crud->set_primary_key('id_concesion_minera', 'concesion_minera');
        $crud->set_subject('Patentes');
        $crud->columns('numero_formulario', 'fecha_resolucion', 'numero_resolucion', 'cantidad_solicitada', 'cantidad_asignada', 'nombre_concesion', 'nombre_empresa');
        $crud->callback_column('nombre_concesion', array($this, '_concesion'));
        $crud->callback_column('nombre_empresa', array($this, '_concesionario'));
        $crud->display_as('numero_formulario', 'Nro Formulario Inscripcion')
                ->display_as('padron_nacional', 'Nro Padron Nacional')
                ->display_as('nombre_concesion', 'Concesion Minera')
                ->display_as('nombre_empresa', 'Concesionario');
        //$crud->add_action('Ver Pago de patentes', '', 'patente_central/patentes', 'edit-icon');
        $crud->fields('nombre_concesion', 'numero_formulario', 'padron_nacional', 'cantidad_solicitada', 'cantidad_asignada', 'numero_resolucion', 'fecha_resolucion');
        $crud->field_type('fecha_resolucion', 'date')
                ->field_type('numero_formulario', 'readonly')
                ->field_type('nombre_concesion', 'readonly')
                ->field_type('padron_nacional', 'readonly');
        $crud->required_fields('numero_resolucion', 'fecha_resolucion');
        $crud->unset_add()
                ->unset_delete()
                ->unset_print()
                ->unset_export();

        $output = $crud->render();
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