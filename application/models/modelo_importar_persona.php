<?php

class modelo_importar_persona extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    

    ///////////////////// CONSULTAS PARA SABER REPORTE DE CONCESIONES CON PAGOS INCOMPLETOS
     function listar_concesiones(){
        $sql="
                SELECT *
                FROM concesion_minera 
                WHERE nombre_empresa IS NULL
                        OR nombre_empresa =''
                ORDER BY id_concesion_minera
                --OFFSET 0 LIMIT 100
        ";
        $consulta = $this->db->query($sql);
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return FALSE;
    }
    
    function listar_idPersonaConcesion(){
        $sql="SELECT conmin.id_concesion_minera, per2.id_persona
                FROM concesion_minera AS conmin
                     INNER JOIN persona AS per ON(conmin.id_concesion_minera=per.id_concesion_minera),
                     persona2 AS per2

                WHERE 	per.nombre_persona = per2.nombre_persona
                        AND per.paterno_persona = per2.paterno_persona
                        AND per.materno_persona = per2.materno_persona
                ORDER BY id_persona
        ";
        $consulta = $this->db->query($sql);
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return FALSE;
    }
    
    
}