<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');

        $this->load->model('modelo_login', '', TRUE);
        $this->load->library(array('table', 'session'));
    }

    function index() {
        $this->session->sess_destroy(); //destroza todos los datos de las seciones
        $this->load->view('login.php');
    }

    function patente_regional() {       
        $usuario = $this->input->post('usuario');
        $password = $this->input->post('password');
        $registro = $this->modelo_login->verificarLogin($usuario, $password);

        if (!$registro) {
            $data['mensaje_error'] = TRUE;
            $this->load->view('login.php', $data);
            
        } else {
            $data['usuario'] = $registro->nombre . ' ' . $registro->primer_apellido . ' ' . $registro->segundo_apellido;
            $tipoUsuario = strtolower($registro->usuario_tipo);
            //-Recupera datos de regional
            $this->db->where('id_regional', $registro->id_regional);
            $datosRegional = $this->db->get('regional')->row();
            //- Almacena datos en las cesiones
            $this->session->set_userdata('regional', $datosRegional->nombre_regional);
            $this->session->set_userdata('id_usuario', $registro->id_usuario);
            $this->session->set_userdata('usuario_nombre', $data['usuario']);
            $this->session->set_userdata('usuario_tipo', $tipoUsuario);
            
            
            switch ($tipoUsuario) {
                case 'patente_central':
                    redirect('/patente_central');
                    break;
                case 'patente_regional':
                    redirect('/patente_regional');
                    break;
                case 'administrador':
                    redirect('/administrador');
                    break;
                case 'archivo':
                    redirect('/archivo');
                    break;
                case 'informatica':
                    redirect('/informatica');
                    break;
                case 'administrativo':
                    redirect('/administrativo');
                    break;
                case 'director':
                    redirect('/director');
                    break;
            }
        }
    }

}