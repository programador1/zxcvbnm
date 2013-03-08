<?php
class Funciones_comunes extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->library('session');
    }
    //INFORMACION CONCESION MINERA ------------------------------------------------------------------------------------------------------------------
    function informacion_concesionMinera($id_concesion_minera) {
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $datosConcesionMinera = $this->db->get('concesion_minera')->row();        
        $vistaDatosConcesion=$this->load->view('informacion_concesion_minera.php', $datosConcesionMinera, TRUE);
        return $vistaDatosConcesion;
    }
  
    //INFORMACION PATENTES DE CONCESION MINERA ------------------------------------------------------------------------------------------------------------------
    function informacion_patentes($id_concesion_minera) {
        $crud = new grocery_CRUD();
        $crud->where('id_concesion_minera', $id_concesion_minera);
        $crud->where('estado_formulario_pago_patente', 'PAGADO');
        $crud->order_by('fecha_registro_sistema', 'desc');
        $crud->set_table('patentes');
        $crud->set_primary_key('id_patentes', 'patentes');
        $crud->set_subject('Patentes');
        $crud->set_primary_key('id_concesion_minera', 'concesion_minera');
        $crud->columns('importe_gestion', 'importe', 'nro_formulario_pago_patente', 'banco', 'fecha_pago', 'fecha_abono', 'observaciones');
        $crud->fields('importe_gestion', 'importe', 'nro_formulario_pago_patente', 'banco', 'fecha_pago', 'fecha_abono', 'observaciones');
        $crud->required_fields('importe_gestion', 'importe', 'nro_formulario_pago_patente', 'banco', 'fecha_pago', 'fecha_abono');
        $crud->display_as('importe_gestion', 'Gestion')
             ->display_as('nro_formulario_pago_patente', 'Nro Boleta');
        $crud->field_type('fecha_pago', 'date')
             ->field_type('fecha_abono', 'date');
        $crud->unset_operations();
        $output = $crud->render();        
        $vistaDatosPatentes= $this->load->view('vista_grocerycrud.php', $output, TRUE);
        return $vistaDatosPatentes;
    }
    //Mensajes -------------------------------------------------------------------------------------------------------------------------------------
    function mensaje(){
        $crud = new grocery_CRUD();
        $crud->set_table('mensaje')
             ->set_primary_key('id_mensaje', 'mensaje')
             ->set_subject('Mensaje');
       $crud->set_relation_n_n('para', 'mensaje_para', 'usuario', 'id_mensaje', 'id_usuario', "nombre||' '||primer_apellido||' '||segundo_apellido")
            ->set_primary_key('id_usuario', 'usuario');
       
       $crud->fields('id_usuario', 'asunto','para', 'estado','mensaje', 'fecha_creacion');
       $crud->field_type('id_usuario', 'hidden',$this->session->userdata('id_usuario'))
            ->field_type('fecha_creacion', 'hidden',date('Y-m-d h:m:s'))
            ->field_type('estado', 'hidden','ENVIADO');
       $crud->required_fields('asunto','Para', 'estado','mensaje');
       
        $output = $crud->render();
        return $output;
    }
    
    //Cambio de Password -----------------------------------------------------------------------------------------------------------------------------
    function usuario_cambiarPassword() {
        $crud = new grocery_CRUD();
        $crud->set_table('usuario');
        $crud->set_primary_key('id_usuario', 'usuario');
        $crud->set_subject('Password');
        $crud->set_primary_key('id_regional', 'regional');
        $crud->set_relation('id_regional', 'regional', 'nombre_regional');
        $crud->fields('usuario_nombre','usuario_password');
        $crud->display_as('usuario_nombre','Nombre de Usuario')
             ->display_as('usuario_password', 'Nuevo Password');
        $crud->field_type('usuario_password', 'password')
             ->field_type('usuario_nombre','readonly');
        $crud->callback_edit_field('usuario_password',array($this,'_user_edit'))
             ->callback_add_field('usuario_password',array($this,'_user_edit'));        
        $crud->set_rules('usuario_password', 'Password', 'trim|required|matches[confirmar_password]')
             ->set_rules('confirmar_password', 'Confirmar Password', 'trim|required');
        $crud->unset_add()
             ->unset_delete()
             ->unset_print()
             ->unset_export()
             ->unset_list();
        $crud->unset_back_to_list();
        
       
        $controlador=$this->uri->segment(1);
        $crud->set_lang_string('update_success_message', 'Su password a sido correctamente Almacenados en la Base de datos.<br/> 
                   <h3><a href="' . site_url($controlador) . '"> Volver a inicio</a>  </h3>
                 
                 <div style="display:none">
                 '
        );
        $output = $crud->render();
        return $output;
    }
    function _user_edit(){
        $html = '<input type="password" name="usuario_password" /> </div></div>';
        $html.= '<div id="confirmar_password_field_box" class="form-field-box odd">';
        $html.= '<div id="confirmar_password_display_as_box" class="form-display-as-box"> Confirmar Password* : </div>';
        $html.= '<div id="confirmar_password_input_box" class="form-input-box">';
        $html.= ' <input type="password" name="confirmar_password" />';
        //$html.= '</div></div>';
        return $html; 
    }
    
}