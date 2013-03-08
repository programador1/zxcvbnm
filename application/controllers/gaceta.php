<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gaceta extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->library('session');
        $this->load->helper('sergeotecmin');
        $this->load->model('modelo_gaceta', '', TRUE);
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
    function pagos_dobles() {
        $concesionMinera = $this->modelo_gaceta->listar_cuadriculas();        
        
        $this->load->library('table');
        $this->table->set_heading('Nro inscripcion', 'Concesion', 'Concesionario', 'Fecha Resolucion', 'Cantidad Asignada', 'Gestion', 'Importe', 'Importe Calculado');
        foreach ($concesionMinera->result() AS $row) {
            $importeCalculado='Sin fecha de pago';
            $gestion = '';
            $importe = '';
            $patentes = $this->modelo_gaceta->pago_patentesDobles($row->id_concesion_minera, '2011');
            if ($patentes) {
                $datosImporte = $this->modelo_gaceta->datos_importe($patentes->fecha_pago);
                $gestion = $patentes->importe_gestion;
                $importe = $patentes->importe;
                if ($row->fecha_resolucion >= '2007-01-01')
                    $importeCalculado = $datosImporte->importe_cuadricula * $row->cantidad_asignada;
                else
                    $importeCalculado = $datosImporte->importe_cuadricula_progresivo * $row->cantidad_asignada;
            }
            if($importe > $importeCalculado)
            $this->table->add_row($row->numero_formulario, $row->nombre_concesion, $row->concesionario, $row->fecha_resolucion, $row->cantidad_asignada, $gestion, $importe, $importeCalculado);
        }
        echo utf8_decode($this->table->generate());
        
        //echo utf8_decode($sql);
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