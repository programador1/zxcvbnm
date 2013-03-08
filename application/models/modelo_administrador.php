<?php

class modelo_administrador extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function concesiones_vigentes_con_fechaResolucion(){
        $sql="
                SELECT DISTINCT conmin.*
                FROM concesion_minera AS conmin
                        INNER JOIN patentes AS pat ON(conmin.id_concesion_minera = pat.id_concesion_minera)
                    WHERE conmin.estado_concesion = 'VIGENTE'
                        AND conmin.tipo_concesion = 'CUADRICULA'
                        AND conmin.fecha_resolucion IS NOT NULL
        ";
        $consulta = $this->db->query($sql);        
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return FALSE;
        
               
    }
    
    function concesiones_vigentes_con_fechaInscripcion(){
        $sql="
                SELECT DISTINCT conmin.*
                FROM concesion_minera AS conmin
                        INNER JOIN patentes AS pat ON(conmin.id_concesion_minera = pat.id_concesion_minera)
                    WHERE conmin.estado_concesion = 'VIGENTE'
                        AND conmin.tipo_concesion = 'PERTENENCIA'
                        AND conmin.fecha_inscripcion IS NOT NULL
        ";
        $consulta = $this->db->query($sql);        
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return FALSE;
        
               
    }
    
    function patentes_concesiones($id_concesion_minera){
        $sql="
                SELECT *
                FROM patentes AS pat
                WHERE pat.id_concesion_minera='$id_concesion_minera'
            
        ";
        $consulta = $this->db->query($sql);        
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return FALSE;
        
    }


}