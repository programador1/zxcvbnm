<div id="tabs">   
    
    <?php 
        $cont=1;
        $htmlMenu='';
        $htmlContenido='';
     
        while (list($menu, $menuContenido) = each($contenido)) {             
            $htmlMenu.='<li><a href="#contenido'.$cont.'">'.$menu.'</a></li>';
            $htmlContenido.='<div id="contenido'.$cont.'"><p>'.$menuContenido.'</p></div>';
            $cont++;
        } 
    ?>
    <ul>
        <?php   echo $htmlMenu;     ?>
    </ul>
    <?php   echo $htmlContenido;     ?>
</div>
<br /><br />
<script type="text/javascript">
    jQuery(function() {
        jQuery( "#tabs" ).tabs();
    });
</script>
