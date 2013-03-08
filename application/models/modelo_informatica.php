<?php

class modelo_informatica extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    

    ///////////////////// CONSULTAS PARA SABER REPORTE DE CONCESIONES CON PAGOS INCOMPLETOS
     function concesiones_vigentes_con_fechaResolcion(){
        $sql="
                SELECT DISTINCT conmin.*
                FROM concesion_minera AS conmin                        
                WHERE conmin.estado_concesion='VIGENTE'
                        AND conmin.tipo_concesion='CUADRICULA'
                        AND conmin.fecha_resolucion IS NOT NULL 
                ORDER BY conmin.fecha_resolucion desc
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
                        AND conmin.\"NACIONALIZADA\"<>TRUE
                        ORDER BY conmin.fecha_inscripcion desc
        ";
        $consulta = $this->db->query($sql);        
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return FALSE;       
               
    }
    
    function patentes_concesiones($id_concesion_minera){
        $sql="  SELECT *
                FROM patentes AS pat
                WHERE pat.id_concesion_minera='$id_concesion_minera'                
        ";
        $consulta = $this->db->query($sql);        
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return FALSE;
    }
//--------------------------------------------------------------------------------------------------------------
//-CONSULTAS PARA PAGO MENOS ----------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
      function concesiones_vigentes(){
        $sql="
                (SELECT DISTINCT conmin.*
                FROM concesion_minera AS conmin                        
                WHERE conmin.estado_concesion='VIGENTE'
                        AND conmin.tipo_concesion='CUADRICULA'
                        AND conmin.fecha_resolucion IS NOT NULL 
                ORDER BY conmin.fecha_resolucion desc
                )
                UNION
                (
                    SELECT DISTINCT conmin.*
                    FROM concesion_minera AS conmin
                        INNER JOIN patentes AS pat ON(conmin.id_concesion_minera = pat.id_concesion_minera)
                    WHERE conmin.estado_concesion = 'VIGENTE'
                        AND conmin.tipo_concesion = 'PERTENENCIA'
                        AND conmin.fecha_inscripcion IS NOT NULL
                        AND conmin.\"NACIONALIZADA\"<>TRUE
                        ORDER BY conmin.fecha_inscripcion desc
                )
        ";
        //echo $sql; exit;
        $consulta = $this->db->query($sql);
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return FALSE;
    }
    function patentes_calculoPago($id_concesion_minera){
        //0. Lista todos los pagos de patentes de una concesion
        //1. Suma los importes de una misma gestion
        //2. Saca la fecha maxima de pago de patente
        $sql="  SELECT importe_gestion, SUM(importe) AS importe, MAX(fecha_pago::date) AS fecha_pago
                FROM patentes 
                WHERE id_concesion_minera='$id_concesion_minera'
                GROUP BY id_concesion_minera, importe_gestion
                ORDER BY importe_gestion desc
        ";
        $consulta = $this->db->query($sql);        
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return FALSE;
    }
    function patente_tarifa($fecha_pago){
        //0. Verifica la tarifa que tiene que pagar de acuerdo a la fecha de pago que realizo
        $sql="  SELECT *
                FROM importe_patente
                WHERE '$fecha_pago' BETWEEN fecha_inicio_pago AND fecha_final_pago
        ";
        //echo $sql;exit();
        $consulta = $this->db->query($sql); 
        
        if ($consulta->num_rows() > 0)
            return $consulta->row();
        else
            return FALSE;
    }
    function operador_aritmetico($valor1,$valor2,$operador){
        switch($operador){
            case '+':
                    $sql = "SELECT round (($valor1 + $valor2),2) AS resultado";
                break;
            case '-':
                    $sql = "SELECT round (($valor1 - $valor2),2) AS resultado";
                break;
            case '*':
                    $sql = "SELECT round (($valor1 * $valor2),2) AS resultado";
                break;
            case '/':
                    $sql = "SELECT round (($valor1 / $valor2),2) AS resultado";
                break;
            case '<':
                    $sql = "SELECT $valor1 < $valor2 AS resultado";
                break; 
        }
        
        $consulta = $this->db->query($sql); 
        $r = $consulta->row();
        return $r->resultado;
    }
}