<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Importar_persona extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->library('session');
        $this->load->helper('sergeotecmin');
        $this->load->model('modelo_importar_persona', '', TRUE);
    }

    function _vista_principal($output = null) {
        $this->load->view('index_archivo.php', $output);
    }

    function index() {
        $this->_vista_principal((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

//===================================================================================================
//- Funcion que prepara inserts a la tabla persona
//===================================================================================================
    function sql_insert_persona() {
        $resultado = $this->modelo_importar_persona->listar_concesiones();
        $campos = 'INSERT INTO persona(
            nombre_persona, paterno_persona, materno_persona, apellido_casada,
            documento_identidad, numero_identidad, lugar_expedido,id_concesion_minera)';
        $sql = '';
        foreach ($resultado->result() AS $row) {
            $nombre = explode('/', $row->nombre_persona);
            $paterno = explode('/', $row->paterno_persona);
            $materno = explode('/', $row->materno_persona);
            $casada = explode('/', $row->apellido_casada);
            $identidad = explode('/', $row->numero_identidad);
            $expedido = explode('/', $row->numero_identidad);

            foreach ($nombre AS $id => $valor) {
                $no = '';
                $pa = '';
                $ma = '';
                $ca = '';
                $ci = '';
                $ex = '';
                if (array_key_exists($id, $nombre))
                    $no = str_replace("'", "''", trim($nombre[$id]));
                if (array_key_exists($id, $paterno))
                    $pa = str_replace("'", "''", trim($paterno[$id]));
                if (array_key_exists($id, $materno))
                    $ma = str_replace("'", "''", trim($materno[$id]));
                if (array_key_exists($id, $casada))
                    $ca = str_replace("'", "''", trim($casada[$id]));
                if (array_key_exists($id, $identidad))
                    $ci = preg_replace("/[^0-9]/", '', trim($identidad[$id]));
                if (array_key_exists($id, $expedido))
                    $ex = preg_replace('/[^a-zA-Z\_]/', '', trim($identidad[$id]));

                //if (strlen($ex)>8)
                $sql.="VALUES ('" . $no . "', '" . $pa . "', '" . $ma . "', '" . $ca . "', 'CI','" . $ci . "', '" . $ex . "', " . $row->id_concesion_minera . "); <br />";
                
            }
        }
        echo utf8_decode($sql);
    }
    
    function sql_insert_concesionario() {
        $resultado = $this->modelo_importar_persona->listar_idPersonaConcesion();
        $sql='';
        foreach ($resultado->result() AS $row) {
            $idConcesionMinera= $row->id_concesion_minera;
            $idPersona= $row->id_persona;
          
                //if (strlen($ex)>8)
                $sql.="INSERT INTO concesionario(id_concesion_minera, id_persona) VALUES (" . $idConcesionMinera .", ".$idPersona."); <br />";
                
            
        }
        echo utf8_decode($sql);
    }
    
    function sql_insert_empresa() {
        $resultado = $this->modelo_importar_persona->listar_concesiones2();
        $campos = 'INSERT INTO empresa(
                    nombre_empresa,id_concesion_minera)';
        $sql = '';
        foreach ($resultado->result() AS $row) {
            $empresa = explode('/', $row->nombre_empresa);
            foreach ($empresa AS $id => $valor) {
                $em = '';                
                if (array_key_exists($id, $empresa))
                    $em = trim($empresa[$id]);

                //if (strlen($ex)>8)
                $sql.="VALUES ('" . $em . "', " . $row->id_concesion_minera . "); <br />";
                
            }
        }
        echo utf8_decode($sql);
    }
}