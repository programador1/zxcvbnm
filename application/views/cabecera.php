<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!-- ---------------  INICIO grocerycrud -------------------------- -->
        <?php
        if (!empty($css_files)) {
            foreach ($css_files as $file):
                ?>
                <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
            <?php endforeach; ?>
            <?php foreach ($js_files as $file): ?>
                <script src="<?php echo $file; ?>"></script>
            <?php
            endforeach;
        }
        ?>
        <!-- ---------------  FIN grocerycrud -------------------------- -->

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content="SERGEOTECMIN" />
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
       
        <link href="<?php echo base_url();?>estilo/images/favicon_sergeotecmin.ico" rel="shortcut icon">
            
        <title>SERGEOTECMIN</title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/style_all.css" type="text/css" media="screen" />



        <!-- to choose another color scheme uncomment one of the foloowing stylesheets and wrap styl1.css into a comment -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/style3.css" type="text/css" media="screen" />

        <!-- 
        <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/style2.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/style3.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/style4.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/style5.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/style6.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/style7.css" type="text/css" media="screen" />
        -->


        <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/jquery-ui.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/jquery.wysiwyg.css" type="text/css" media="screen" />
        

        <!--Internet Explorer Trancparency fix-->
        <!--[if IE 6]>
        <script src="<?php echo base_url(); ?>estilo/js/ie6pngfix.js"></script>
        <script>
          DD_belatedPNG.fix('#head, a, a span, img, .message p, .click_to_close, .ie6fix');
        </script>
        <![endif]--> 

        <script type='text/javascript' src='<?php echo base_url(); ?>estilo/js/jquery.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>estilo/js/jquery-ui.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>estilo/js/jquery.validate.js'></script>
        <script type='text/javascript' src="<?php echo base_url(); ?>estilo/js/jquery_print_element.js" type="text/javascript"></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>estilo/js/jquery.ui.datepicker-es.js'></script>

        <script type='text/javascript' src='<?php echo base_url(); ?>estilo/js/jquery.wysiwyg.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>estilo/js/custom.js'></script>
        
        <script src="<?php echo base_url(); ?>estilo/js/jquery.fcbkcomplete.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>estilo/css/style.css" type="text/css" media="screen" charset="utf-8" />

        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery("#simplePrint").click(function() {
                    printElem({});
                });

                jQuery("#volver").click(function() {
                    parent.history.back(); 
                    return false; 
                });

            });
            function printElem(options){
                jQuery('#toPrint').printElement(options);
                //jQuery("body").css('background', 'none');
            }




        </script>


    </head>

    <body>
        <!-- this is the content for the dialog that pops up on window start
        <div id="dialog" title="Welcome to flexy admin">
       	<p>Hello admin! welcome back.<br/> You got <strong>1 new Message</strong> in your inbox</p>
        <p>This is a messagebox, you can fill it with content of your choice ;)</p>
        </div>
        -->

        <div id="top">

            <div id="head">
                <h1 class="logo">
<!--                    <a href="<?php echo site_url(); ?>">SERGEOTECMIN</a>-->
                </h1>

                <div class="head_memberinfo">
<!--                    <div class="head_memberinfo_logo">
                        <span> 0 </span>
                        <img src="<?php echo base_url(); ?>estilo/images/unreadmail.png" alt=""/>
                    </div>-->

                    <span class='memberinfo_span'>
                        Bienvenido 
                        <?php
                        //if (!empty($this->session->userdata('usuario_nombre')))
                        echo $this->session->userdata('usuario_nombre');
                        ?>

                    </span>


                    <span>
                        <a href="<?php echo site_url('login'); ?>">Salir</a>
                    </span>

                    <span class='memberinfo_span2'>
                        <?php $urlMensaje=$this->uri->segment(1).'/mensaje'; ?>
<!--                        <a href="<?php echo site_url($urlMensaje); ?>">0 Mensaje Privado recibido</a>-->
                    </span>
                    <br />
                    </span>
                    <?php
                    echo 'MODULO &nbsp;-> ' . strtoupper($this->session->userdata('usuario_tipo')).' [' . strtoupper($this->session->userdata('regional')).']';
                    ?>
                    </span>
                </div><!--end head_memberinfo-->

            </div><!--end head-->
            <div id="bg_wrapper">

                <div id="main">

                    <div id="content">

                        <div class="content_block">
                            <h2 class="jquery_tab_title">
                                <?php
                                if (!empty($titulo))
                                    echo $titulo;
                                else
                                    echo 'Principal';
                                ?>
                            </h2>

                            <?php
                            ////////// muestra vista de grocery crud
                            
                            if (!empty($output)) {
                                echo '<div>';
                                    if (!empty($datosAdicionalesSuperior))
                                        echo $datosAdicionalesSuperior;
                                    echo $output;                                    
                                echo '</div>';
                             } ?>