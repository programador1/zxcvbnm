<?php

class modelo_patente_regional extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //-- Perminite calcular el monto total que cancelo por una Gestion (si realizo mas de un pago en una getion, suma todos sus pagos)
    function pago_patentesPorGestion($id_concesion_minera, $gestion) {
        $sql = "SELECT importe_gestion, SUM(importe) AS importe_cancelado, max(fecha_pago::date) AS fecha_pago
                FROM patentes
                WHERE id_concesion_minera = '$id_concesion_minera'
                      AND importe_gestion = '$gestion'
                      AND estado_formulario_pago_patente = 'PAGADO'
                GROUP BY importe_gestion
        ";
        $consulta = $this->db->query($sql);
        //echo $sql; exit();
        if ($consulta->num_rows() > 0)
            return $consulta->row();
        else
            return FALSE;
    }

    function datos_importePorGestion($fecha_pago) {
        $sql = "SELECT *
                FROM importe_patente
                WHERE   fecha_inicio_pago <= '$fecha_pago'
                        AND fecha_final_pago >= '$fecha_pago'
        ";
        $consulta = $this->db->query($sql);
        //echo $sql; exit();
        if ($consulta->num_rows() > 0)
            return $consulta->row();
        else
            return FALSE;
    }

    //--datos para modulo de pago de patenntes erik
    function personas() {
        $sql = "SELECT 
                    per.id_persona AS key, 
                    TRIM(COALESCE(per.numero_identidad,NULL,'')||' '||COALESCE(per.nombre_persona,NULL,'')||' '||COALESCE(per.paterno_persona,NULL,'')||' '||COALESCE(per.materno_persona,NULL,'')||' '||COALESCE(per.apellido_casada,NULL,'')) AS value
                FROM 
                    persona AS per
                WHERE 
                    TRIM(COALESCE(per.nombre_persona,NULL,'')||' '||COALESCE(per.paterno_persona,NULL,'')||' '||COALESCE(per.materno_persona,NULL,'')||' '||COALESCE(per.apellido_casada,NULL,'')) <> '' 
                OR 
                    TRIM(COALESCE(per.nombre_persona,NULL,'')||' '||COALESCE(per.paterno_persona,NULL,'')||' '||COALESCE(per.materno_persona,NULL,'')||' '||COALESCE(per.apellido_casada,NULL,'')) IS NULL
                ORDER BY value";
        $consulta = $this->db->query($sql);
        if ($consulta->num_rows() > 0)
            return $consulta->result_array();
        else
            return FALSE;
    }

    function persona($id_persona) {
        $sql = "SELECT * FROM persona WHERE id_persona = '$id_persona'";
        $consulta = $this->db->query($sql);
        if ($consulta->num_rows() > 0)
            return $consulta->row_array();
        else
            return FALSE;
    }

    function minerales() {
        $sql = "SELECT 
                    min.id_mineral AS key, 
                    nombre AS value
                FROM 
                    mineral AS min
                ORDER BY nombre";
        $consulta = $this->db->query($sql);
        if ($consulta->num_rows() > 0)
            return $consulta->result_array();
        else
            return FALSE;
    }

}