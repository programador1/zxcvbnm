<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gaceta extends CI_Controller {

    function __construct() {
        parent::__construct();        
        $this->load->model('modelo_gaceta', '', TRUE);
    }

    function _vista_principal($output = null) {
        $this->load->view('index_archivo.php', $output);
    }

    function index() {
        $this->_vista_principal((object) array('output' => '', 'js_files' => array(), 'css_files' => array()));
    }

//===================================================================================================
//- Funcion para la gaceta de caducidad
//===================================================================================================
    function gaceta_caducidad() {
        $gestionGaceta = '2012';
        $concesionMinera = $this->modelo_gaceta->gaceta_citacion();

        $this->load->library('table');
        $this->table->set_heading('Tipo', 'Nro inscripcion', 'Concesion', 'Provincia', 'Canton', 'Regional', 'Estado', 'Observacion');
        foreach ($concesionMinera->result() AS $row) {
            //controla si realizo pago gestion 2012
            $patentes = $this->modelo_gaceta->pago_patentesDobles($row->id_concesion_minera, $gestionGaceta);
            if ($patentes) {
                $gestion = $patentes->importe_gestion;
                $importe = $patentes->importe;
                $datosImporte = $this->modelo_gaceta->datos_importe($patentes->fecha_pago);
                if ($gestion == 'S/G') {
                    //-SIN GESTION
                    $this->table->add_row($row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $row->provincia, $row->canton, $row->regional, 'Sin Gestion', '');
                } else {
                    $importe = $patentes->importe;
                    $datosSimpleProgresivo = $this->_calcular_simpleProgresivo($row->id_concesion_minera, $datosImporte, $gestionGaceta);
                    $tipoImporte = $datosSimpleProgresivo['tipoImporte'];
                    $importeCalculado = $datosSimpleProgresivo['importeCalculado'];
                    $fechaResolucion = $datosSimpleProgresivo['fechaResolucion'];
                    if ($importe >= $importeCalculado) {
                        //-PAGO CORRECTO
                        $this->table->add_row($row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $row->provincia, $row->canton, $row->regional, 'Pago Realizado', '');
                    } else {
                        //-PAGO MENOS
                        $datosImporte = $this->modelo_gaceta->datos_importe('01-01-2013');
                        $datosSimpleProgresivo = $this->_calcular_simpleProgresivo($row->id_concesion_minera, $datosImporte, $gestionGaceta);
                        $tipoImporte = $datosSimpleProgresivo['tipoImporte'];
                        $importeCalculado = $datosSimpleProgresivo['importeCalculado'];
                        $fechaResolucion = $datosSimpleProgresivo['fechaResolucion'];
                        $deudaPendiente = $importeCalculado - $importe;
                        $this->table->add_row($row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $row->provincia, $row->canton, $row->regional, 'Pago Menos', '
                                                 Fecha resolucion : ' . $fechaResolucion . ' ==> Importe Cancelado : ' . $importe . ' ==> Importe Calculado : ' . $importeCalculado . '
                                                ');
                    }
                }
            } else {
                $this->table->add_row($row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $row->provincia, $row->canton, $row->regional, 'No realizo pago');
            }
        }
        echo utf8_decode($this->table->generate());

        //echo utf8_decode($sql);
    }  
    
    function gaceta_caducidad2() {
        $gestionGaceta = '2012';
        $concesionMinera = $this->modelo_gaceta->gaceta_citacion();

        $this->load->library('table');
        $this->table->set_heading('Tipo', 'Nro inscripcion', 'Concesion', 'Concesionario','Monto pagado (Bs)', 'Monto a pagar (Bs)', 'Deuda pendiente (Bs)', 'Cantidad', 'Estado', 'Observacion');
        foreach ($concesionMinera->result() AS $row) {
            //controla si realizo pago gestion 2012
            $patentes = $this->modelo_gaceta->pago_patentesDobles($row->id_concesion_minera, $gestionGaceta);
            if ($row->unidad == 'CUADRICULA')
                $unidad = ' [CUA]';
            else
                $unidad = ' [' . $row->unidad . ']';
            $cantidadAsignada = $row->cantidad_asignada . $unidad;
            $concesionario = $this->_concesionario($row->id_concesion_minera);
            $gestion = '';
            $importe = 0;
            $importeCalculado = 0;
            $deudaPendiente = 0;
            if ($patentes) {
                $gestion = $patentes->importe_gestion;
                $importe = $patentes->importe;
                $datosImporte = $this->modelo_gaceta->datos_importe($patentes->fecha_pago);
                $datosSimpleProgresivo = $this->_calcular_simpleProgresivo($row->id_concesion_minera, $datosImporte, $gestionGaceta);
                $tipoImporte = $datosSimpleProgresivo['tipoImporte'];
                $importeCalculado = $datosSimpleProgresivo['importeCalculado'];
                $fechaResolucion = $datosSimpleProgresivo['fechaResolucion'];
                $deudaPendiente = $importeCalculado - $importe;
                if ($gestion == 'S/G') {
                    //-SIN GESTION
                    $this->table->add_row($row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $concesionario, $importe, $importeCalculado, $deudaPendiente, $cantidadAsignada, 'Sin Gestion', '');
                } else {

                    if ($importe >= $importeCalculado) {
                        //-PAGO CORRECTO
						//$this->table->add_row($row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $concesionario, $importe, $importeCalculado, $deudaPendiente, $cantidadAsignada, 'Pago Realizado', '');
                    } else {
                        $datosImporte = $this->modelo_gaceta->datos_importe('01-01-2013');
                        $datosSimpleProgresivo = $this->_calcular_simpleProgresivo($row->id_concesion_minera, $datosImporte, $gestionGaceta);
                        $tipoImporte = $datosSimpleProgresivo['tipoImporte'];
                        $importeCalculado = $datosSimpleProgresivo['importeCalculado'];
                        $fechaResolucion = fecha_literal($datosSimpleProgresivo['fechaResolucion'],'5');
                        $deudaPendiente = $importeCalculado - $importe;
                        //-PAGO MENOS
                        $this->table->add_row($row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $concesionario, $importe, $importeCalculado, $deudaPendiente, $cantidadAsignada, 'Pago Menos', '
                                                 Fecha resolucion : ' . $fechaResolucion 
                                                );
                    }
                }
            } else {
                $datosImporte = $this->modelo_gaceta->datos_importe('01-01-2013');
                $datosSimpleProgresivo = $this->_calcular_simpleProgresivo($row->id_concesion_minera, $datosImporte, $gestionGaceta);
                $tipoImporte = $datosSimpleProgresivo['tipoImporte'];
                $importeCalculado = $datosSimpleProgresivo['importeCalculado'];
                $fechaResolucion = $datosSimpleProgresivo['fechaResolucion'];
                $deudaPendiente = $importeCalculado - $importe;
                $this->table->add_row($row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $concesionario, $importe, $importeCalculado, $deudaPendiente, $cantidadAsignada, 'No realizo pago');
            }
        }
        echo utf8_decode($this->table->generate());

        //echo utf8_decode($sql);
    }
    function gaceta_caducidadFin() {
        $gestionGaceta = '2012';
        $concesionMinera = $this->modelo_gaceta->gaceta_citacion();

        $this->load->library('table');
        $this->table->set_heading('Nro','Tipo', 'Nro inscripcion', 'Concesion', 'Concesionario','Departamento','Provincia', 'Canton', 'Regional', 'Estado', 'Observacion');
        $nro=0;
        foreach ($concesionMinera->result() AS $row) {
            $nro++;
            //controla si realizo pago gestion 2012
            $patentes = $this->modelo_gaceta->pago_patentesDobles($row->id_concesion_minera, $gestionGaceta);
            $concesionario = $this->_concesionario($row->id_concesion_minera);
            if ($patentes) {
                $gestion = $patentes->importe_gestion;
                $importe = $patentes->importe;
                $datosImporte = $this->modelo_gaceta->datos_importe($patentes->fecha_pago);
                if ($gestion == 'S/G') {
                    //-SIN GESTION
                    $this->table->add_row($nro, $row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $concesionario, $row->departamento,$row->provincia, $row->canton, $row->regional, 'Sin Gestion', '');
                } else {
                    $importe = $patentes->importe;
                    $datosSimpleProgresivo = $this->_calcular_simpleProgresivo($row->id_concesion_minera, $datosImporte, $gestionGaceta);
                    $tipoImporte = $datosSimpleProgresivo['tipoImporte'];
                    $importeCalculado = $datosSimpleProgresivo['importeCalculado'];
                    $fechaResolucion = $datosSimpleProgresivo['fechaResolucion'];
                    if ($importe >= $importeCalculado) {
                        //-PAGO CORRECTO
                        $this->table->add_row($nro, $row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $concesionario, $row->departamento, $row->provincia, $row->canton, $row->regional, 'Pago Realizado', '');
                    } else {
                        //-PAGO MENOS
                        $datosImporte = $this->modelo_gaceta->datos_importe('01-01-2013');
                        $datosSimpleProgresivo = $this->_calcular_simpleProgresivo($row->id_concesion_minera, $datosImporte, $gestionGaceta);
                        $tipoImporte = $datosSimpleProgresivo['tipoImporte'];
                        $importeCalculado = $datosSimpleProgresivo['importeCalculado'];
                        $fechaResolucion = $datosSimpleProgresivo['fechaResolucion'];
                        $deudaPendiente = $importeCalculado - $importe;
                        $this->table->add_row($nro, $row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $concesionario, $row->departamento, $row->provincia, $row->canton, $row->regional, 'Pago Menos', '
                                                 Fecha resolucion : ' . fecha_literal($fechaResolucion,5) . ' ==> Importe Cancelado : ' . $importe . ' ==> Importe Calculado : ' . $importeCalculado . '
                                                ');
                    }
                }
            } else {
                $this->table->add_row($nro, $row->tipo_concesion, $row->numero_formulario, $row->nombre_concesion, $concesionario, $row->departamento, $row->provincia, $row->canton, $row->regional, 'No realizo pago');
            }
        }
        echo utf8_decode($this->table->generate());

        //echo utf8_decode($sql);
    }
    
    function _concesionario($id_concesion_minera) {
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $resultado = $this->db->get('concesion_minera')->row();
        $nombre = '';        
            if (!empty($resultado->nombre_empresa)) {
                $nombre = $resultado->nombre_empresa;
            } else {
                $nom = explode('/', $resultado->nombre_persona);
                $pat = explode('/', $resultado->paterno_persona);
                $mat = explode('/', $resultado->materno_persona);
                $sw = 0;
                foreach ($nom AS $id => $valor) {                   
                    if ($sw==1) $nombre.=', ';
                    $sw=1;
                    $nombre.= $nom[$id].' '.$pat[$id].' '.$mat[$id];                    
                }
            }
        
        return utf8_decode($nombre);
    }

    function _calcular_simpleProgresivo($id_concesion_minera, $datosImporte, $gestion) {
        $this->db->where('id_concesion_minera', $id_concesion_minera);
        $datosConcesion = $this->db->get('concesion_minera')->row();

        //-- recupera datos que se usaran para controlar
        if (strtolower($datosConcesion->tipo_concesion) === 'cuadricula') {
            $gestionResolucion = substr($datosConcesion->fecha_resolucion, 0, 4);
            $fechaResolucion = $datosConcesion->fecha_resolucion;
        } else {
            $gestionResolucion = substr($datosConcesion->fecha_inscripcion, 0, 4);
            $fechaResolucion = $datosConcesion->fecha_inscripcion;
        }
        ///////////////////////////////////////////////////////////////////////
        //-- Controla si le corresponde pago SIMPLE o PROGRESIVO
        ///////////////////////////////////////////////////////////////////////
        $cantidadAsignada = $datosConcesion->cantidad_asignada;
        switch (strtolower($datosConcesion->tipo_concesion)) {
            case 'cuadricula':
                if ($datosConcesion->unidad === 'HAS')
                    $cantidadAsignada = $cantidadAsignada / 25; //convierte a cuadiculas si son HAS
                $gestionesVigentes = $gestion - $gestionResolucion;    //saca el numero de gestiones de vigencia
                $gestionesVigentes = 1 + $gestionesVigentes;                        // sumamos mas uno, para tomar en cuenta la gestion de la resolucion
                if ($gestionesVigentes >= $datosImporte->gestiones_aplicables_progesivo) {
                    //--Define el costo de importe PROGRESIVO para CUADRICULA
                    $tipoImporte = 'PROGRESIVO';
                    $importe = $datosImporte->importe_cuadricula_progresivo;
                } else {
                    //--Define el costo SIMPLE para CUADRICULA
                    $tipoImporte = 'SIMPLE';
                    $importe = $datosImporte->importe_cuadricula;
                }
                break;
            case 'pertenencia':
                if ($datosConcesion->unidad === 'M2')
                    $cantidadAsignada = $cantidadAsignada / 10000; //convierte a HAS si son M2
                if ($cantidadAsignada > 1000) {
                    //--Define el costo de importe PROGRESIVO para CUADRICULA
                    $tipoImporte = 'PROGRESIVO';
                    $importe = $datosImporte->importe_pertenencia_progresivo;
                } else {
                    //--Define el costo SIMPLE para CUADRICULA
                    $tipoImporte = 'SIMPLE';
                    $importe = $datosImporte->importe_pertenencia;
                }
                break;
        }
        $importeCalculado = $importe * $cantidadAsignada;
        $resultado = array('importe' => $importe, 'tipoImporte' => $tipoImporte, 'importeCalculado' => $importeCalculado, 'fechaResolucion' => $fechaResolucion);

        return $resultado;
    }

//===================================================================================================
//- Funcion que prepara inserts a la tabla persona
//===================================================================================================
    function pagos_dobles() {
        $concesionMinera = $this->modelo_gaceta->listar_cuadriculas();

        $this->load->library('table');
        $this->table->set_heading('Nro inscripcion', 'Concesion', 'Concesionario', 'Fecha Resolucion', 'Cantidad Asignada', 'Gestion', 'Importe', 'Importe Calculado');
        foreach ($concesionMinera->result() AS $row) {
            $importeCalculado = 'Sin fecha de pago';
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
            if ($importe > $importeCalculado)
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