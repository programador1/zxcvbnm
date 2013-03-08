<?php

class modelo_patente_central extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function reporte_diarioPatentes($fechaAbono){
        $sql="
            SELECT conmin.tipo_concesion AS \"tipo\", conmin.estado_concesion AS \"estado\", conmin.numero_formulario AS \"nro_inscripcion\",conmin.nombre_concesion AS \"nombre_concesion\", pat.importe_gestion AS \"gestion\", pat.nro_formulario_pago_patente AS \"nro_boleta\",pat.importe,
                    to_char(pat.fecha_pago,'dd-MM-YYYY') AS \"fecha_pago\",
                    to_char(pat.fecha_abono,'dd-MM-YYYY') AS \"fecha_abono\",                    
                    conmin.regional AS \"regional\"
            FROM patentes AS pat
                    INNER JOIN concesion_minera AS conmin ON(conmin.id_concesion_minera=pat.id_concesion_minera)
            WHERE 	fecha_abono='$fechaAbono' 
            ORDER BY pat.id_patentes            
        ";
        $consulta = $this->db->query($sql);        
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return FALSE;
        
               
    }
    
    function reporte_generalPatentes($fechaInicio, $fechaFin){
        $sql="
                SELECT to_char(pat.fecha_abono,'dd-MM-YYYY') AS fecha_pago,
                COUNT(pat.nro_formulario_pago_patente) AS nro_formularios_procesados, 
                round( SUM (pat.importe)::numeric,2) AS monto_recaudado,
                round( (SUM (pat.importe)*0.02)::numeric,2) AS comision_bancaria,
                round( ((SUM (pat.importe) - (SUM (pat.importe)*0.02)))::numeric, 2) AS ingreso_extracto_bancario	
                FROM patentes AS pat
                WHERE fecha_abono>='$fechaInicio' AND fecha_abono <= '$fechaFin' 
                GROUP BY pat.fecha_abono
                ORDER BY pat.fecha_abono
            
        ";
        $consulta = $this->db->query($sql);        
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return FALSE;
        
    }


    ///////////////////// CONSULTAS PARA SABER REPORTE DE CONCESIONES CON PAGOS INCOMPLETOS
     function concesiones_vigentes_con_fechaResolucion(){
        $sql="
                SELECT DISTINCT conmin.*
                FROM concesion_minera AS conmin                        
                WHERE conmin.estado_concesion='VIGENTE'
                        AND conmin.tipo_concesion='CUADRICULA'
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
    
}