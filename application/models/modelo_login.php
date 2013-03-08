<?php

class modelo_Login extends CI_Model {

    protected $primary_key = null;
    protected $table_name = null;
    protected $relation = array();

    function __construct() {
        parent::__construct();
    }

    function verificarLogin($usuario, $password) {
        $this->db->select('nombre, primer_apellido, segundo_apellido, usuario_tipo, id_usuario, id_regional');
        $this->db->from('usuario');
        $this->db->where('usuario_nombre', $usuario);
        $this->db->where('usuario_password', $password);
        $this->db->where('estado', 'A');
        $query = $this->db->get();
        if ($this->db->count_all_results() > 0)
            return $query->row();
        else
            return FALSE;
    }

}