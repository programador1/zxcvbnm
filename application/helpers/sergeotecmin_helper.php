<?php

if (!defined('BASEPATH'))
    exit('No tiene acceso a esta pagina');

function combo_pagoPatente_lugar($valuePagoPatenteLugar = '') {
    $html = '<select name = "pagoPatente_lugar" >';
    $html.= '<option value="LA PAZ" ' . (('LA PAZ' == $valuePagoPatenteLugar) ? 'selected' : '') . '>         LA PAZ      </option>';
    $html.= '<option value="SUCRE" ' . (('SUCRE' == $valuePagoPatenteLugar) ? 'selected' : '') . '>           SUCRE       </option>';
    $html.= '<option value="COCHABAMBA" ' . (('COCHABAMBA' == $valuePagoPatenteLugar) ? 'selected' : '') . '> COCHABAMBA  </option>';
    $html.= '<option value="POTOSI" ' . (('POTOSI' == $valuePagoPatenteLugar) ? 'selected' : '') . '>         POTOSI      </option>';
    $html.= '<option value="TARIJA" ' . (('TARIJA' == $valuePagoPatenteLugar) ? 'selected' : '') . '>         TARIJA      </option>';
    $html.= '<option value="SANTA CRUZ" ' . (('SANTA CRUZ' == $valuePagoPatenteLugar) ? 'selected' : '') . '> SANTA CRUZ  </option>';
    $html.= '<option value="ORURO" ' . (('ORURO' == $valuePagoPatenteLugar) ? 'selected' : '') . '>           ORURO       </option>';
    $html.= '<option value="TUPIZA" ' . (('TUPIZA' == $valuePagoPatenteLugar) ? 'selected' : '') . '>         TUPIZA      </option>';
    $html.= '</select>';
    return $html;
}

function combo_pagoPatente_banco($valuePagoPatenteBanco = '') {
    $html = '<select name = "pagoPatente_banco" id = "pagoPatente_banco" >';
    $html.= '<option value="BM" ' . (('BM' == $valuePagoPatenteBanco) ? 'selected' : '') . '> BANCO MERCANTIL </option>';
    $html.= '</select>';
    return $html;
}

//- Mensajes error, info, correcto, advertencia
function mensaje($titulo = '', $subTitulo = '', $descripcion = '', $tipo = 'error') {
    switch ($tipo) {
        case 'error':
            $class = 'message error';
            break;
        case 'info':
            $class = 'message tip';
            break;
        case 'correcto':
            $class = 'message success';            
            break;
        case 'advertencia':
            $class = 'message warning';
            break;
    }
    $html='<h3>' . $titulo . '</h3>';
    $html.= '<div class="'.$class.'">';    
    //$html.=$descripcion;
    $html.='<p><strong>' . $subTitulo . '</strong> '.$descripcion.'</p></div>';
    return $html;
}

function derecha($valor) {
    $html = '<div style="text-align:right">' . $valor . '</div>';
    return $html;
}

function centro($valor, $negrita = '', $tamano = '') {
    $estilo = '';
    if ($negrita != '')
        $estilo.=' font-weight: bold;';
    if ($tamano != '')
        $estilo.=' font-size: ' . $tamano . 'px;';
    $html = '<div style="text-align:center; ' . $estilo . '">' . $valor . '</div>';
    return $html;
}

function alinear($valor, $alinear = 'izquierda', $negrita = '', $tamano = '') {
    $estilo = '';
    if ($alinear == 'izquierda')
        $estilo.='text-align:left;';
    if ($alinear == 'derecha')
        $estilo.='text-align:right;';
    if ($alinear == 'centro')
        $estilo.='text-align:center;';
    if ($negrita != '')
        $estilo.=' font-weight: bold;';
    if ($tamano != '')
        $estilo.=' font-size: ' . $tamano . 'px;';
    $html = '<div style="' . $estilo . '">' . $valor . '</div>';
    return $html;
}

function fecha_literal($Fecha, $Formato = 2) {
    $dias = array(1 => 'Lunes', 2 => 'Martes', 3 => 'Mièrcoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sàbado', 7 => 'Domingo');
    $meses = array(1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril', 5 => 'mayo', 6 => 'junio',
        7 => 'julio', 8 => 'agosto', 9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre');
    $aux = date_parse($Fecha);
    switch ($Formato) {
        case 1:  // 04/10/10
            return date('d/m/y', strtotime($Fecha));
        case 2:  //04/oct/10
            return sprintf('%02d/%s/%02d', $aux['day'], substr($meses[$aux['month']], 0, 3), $aux['year'] % 100);
        case 3:   //octubre 4, 2010
            return $meses[$aux['month']] . ' ' . sprintf('%.2d', $aux['day']) . ', ' . $aux['year'];
        case 4:   // 4 de octubre de 2010
            return $aux['day'] . ' de ' . $meses[$aux['month']] . ' de ' . $aux['year'];
        case 5:
            return date('d/m/Y', strtotime($Fecha));
        default:
            return date('d/m/Y', strtotime($Fecha));
    }
}

/* ! 
  @function num2letras ()
  @abstract Dado un n?mero lo devuelve escrito.
  @param $num number - N?mero a convertir.
  @param $fem bool - Forma femenina (true) o no (false).
  @param $dec bool - Con decimales (true) o no (false).
  @result string - Devuelve el n?mero escrito en letra.

 */

function numero_letra($num, $fem = false, $dec = true) {
    $matuni[2] = "dos";
    $matuni[3] = "tres";
    $matuni[4] = "cuatro";
    $matuni[5] = "cinco";
    $matuni[6] = "seis";
    $matuni[7] = "siete";
    $matuni[8] = "ocho";
    $matuni[9] = "nueve";
    $matuni[10] = "diez";
    $matuni[11] = "once";
    $matuni[12] = "doce";
    $matuni[13] = "trece";
    $matuni[14] = "catorce";
    $matuni[15] = "quince";
    $matuni[16] = "dieciseis";
    $matuni[17] = "diecisiete";
    $matuni[18] = "dieciocho";
    $matuni[19] = "diecinueve";
    $matuni[20] = "veinte";
    $matunisub[2] = "dos";
    $matunisub[3] = "tres";
    $matunisub[4] = "cuatro";
    $matunisub[5] = "quin";
    $matunisub[6] = "seis";
    $matunisub[7] = "sete";
    $matunisub[8] = "ocho";
    $matunisub[9] = "nove";

    $matdec[2] = "veint";
    $matdec[3] = "treinta";
    $matdec[4] = "cuarenta";
    $matdec[5] = "cincuenta";
    $matdec[6] = "sesenta";
    $matdec[7] = "setenta";
    $matdec[8] = "ochenta";
    $matdec[9] = "noventa";
    $matsub[3] = 'mill';
    $matsub[5] = 'bill';
    $matsub[7] = 'mill';
    $matsub[9] = 'trill';
    $matsub[11] = 'mill';
    $matsub[13] = 'bill';
    $matsub[15] = 'mill';
    $matmil[4] = 'millones';
    $matmil[6] = 'billones';
    $matmil[7] = 'de billones';
    $matmil[8] = 'millones de billones';
    $matmil[10] = 'trillones';
    $matmil[11] = 'de trillones';
    $matmil[12] = 'millones de trillones';
    $matmil[13] = 'de trillones';
    $matmil[14] = 'billones de trillones';
    $matmil[15] = 'de billones de trillones';
    $matmil[16] = 'millones de billones de trillones';

    //Zi hack
    $numeroFloat = explode('.', $num);
    $num = $numeroFloat[0];

    $num = trim((string) @$num);
    if ($num[0] == '-') {
        $neg = 'menos ';
        $num = substr($num, 1);
    }
    else
        $neg = '';
    while ($num[0] == '0')
        $num = substr($num, 1);
    if ($num[0] < '1' or $num[0] > 9)
        $num = '0' . $num;
    $zeros = true;
    $punt = false;
    $ent = '';
    $fra = '';
    for ($c = 0; $c < strlen($num); $c++) {
        $n = $num[$c];
        if (!(strpos(".,'''", $n) === false)) {
            if ($punt)
                break;
            else {
                $punt = true;
                continue;
            }
        } elseif (!(strpos('0123456789', $n) === false)) {
            if ($punt) {
                if ($n != '0')
                    $zeros = false;
                $fra .= $n;
            }
            else
                $ent .= $n;
        }
        else
            break;
    }
    $ent = '     ' . $ent;
    if ($dec and $fra and !$zeros) {
        $fin = ' coma';
        for ($n = 0; $n < strlen($fra); $n++) {
            if (($s = $fra[$n]) == '0')
                $fin .= ' cero';
            elseif ($s == '1')
                $fin .= $fem ? ' una' : ' un';
            else
                $fin .= ' ' . $matuni[$s];
        }
    }
    else
        $fin = '';
    if ((int) $ent === 0)
        return 'Cero ' . $fin;
    $tex = '';
    $sub = 0;
    $mils = 0;
    $neutro = false;
    while (($num = substr($ent, -3)) != '   ') {
        $ent = substr($ent, 0, -3);
        if (++$sub < 3 and $fem) {
            $matuni[1] = 'una';
            $subcent = 'as';
        } else {
            $matuni[1] = $neutro ? 'un' : 'uno';
            $subcent = 'os';
        }
        $t = '';
        $n2 = substr($num, 1);
        if ($n2 == '00') {
            
        } elseif ($n2 < 21)
            $t = ' ' . $matuni[(int) $n2];
        elseif ($n2 < 30) {
            $n3 = $num[2];
            if ($n3 != 0)
                $t = 'i' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        }else {
            $n3 = $num[2];
            if ($n3 != 0)
                $t = ' y ' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        }
        $n = $num[0];
        if ($n == 1) {
            $t = ' ciento' . $t;
        } elseif ($n == 5) {
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
        } elseif ($n != 0) {
            $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
        }
        if ($sub == 1) {
            
        } elseif (!isset($matsub[$sub])) {
            if ($num == 1) {
                $t = ' mil';
            } elseif ($num > 1) {
                $t .= ' mil';
            }
        } elseif ($num == 1) {
            $t .= ' ' . $matsub[$sub] . '&oacute;n';
        } elseif ($num > 1) {
            $t .= ' ' . $matsub[$sub] . 'ones';
        }
        if ($num == '000')
            $mils++;
        elseif ($mils != 0) {
            if (isset($matmil[$sub]))
                $t .= ' ' . $matmil[$sub];
            $mils = 0;
        }
        $neutro = true;
        $tex = $t . $tex;
    }
    $tex = $neg . substr($tex, 1) . $fin;
    //Zi hack --> return ucfirst($tex);
    if (empty($numeroFloat[1]))
        $numeroFloat[1] = '00';
    $end_num = ucfirst($tex) . ' ' . $numeroFloat[1] . '/100 bolivianos';
    return $end_num;
}

// - BOTONES --------------------------------------------------------------------------------------------------
function boton($valor = 'volver', $url = '') {
    $valor = strtolower($valor);
    $html = '';
    switch ($valor) {
        case 'volver':
            if ($url == '')
                $html = '<a id="volver" href="#" title="Volver"> <img src="' . base_url() . 'estilo/images/boton_volver.png" width="119" height="41" alt="Volver"/> </a>';
            else
                $html = '<a href="' . $url . '" title="Volver"> <img src="' . base_url() . 'estilo/images/boton_volver.png" width="119" height="41" alt="Volver"/> </a>';
            break;
        case 'imrpimir':
            $html = '<a id="simplePrint" href="#" title="Imprimir"> <img src="' . base_url() . 'estilo/images/boton_imprimir.png" width="129" height="41" alt="Imprimir"/> </a>';
            break;
    }
    return $html;
}

// - Define los tipos de usuarios del sistema --------------------------------------------------------------------------------------------------
function tipo_usuario() {
    $tipoUsuario = array(
        'ADMINISTRATIVO',
        'PATENTE_REGIONAL',
        'PATENTE_CENTRAL',
        'ARCHIVO',
        'DIRECTOR',
        'INFORMATICA',
        'ADMINISTRADOR'
    );
    return $tipoUsuario;
}

?>