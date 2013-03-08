<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content="Reflect Template" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
        <link href="<?php echo base_url();?>estilo/images/favicon_sergeotecmin.ico" rel="shortcut icon">
        <title>SERGEOTECMIN</title>
        <link rel="stylesheet" href="<?php echo base_url();?>estilo/css/style_all.css" type="text/css" media="screen" />
        <!-- to choose another color scheme uncomment one of the foloowing stylesheets and wrap styl1.css into a comment -->
        <link rel="stylesheet" href="<?php echo base_url();?>estilo/css/style1.css" type="text/css" media="screen" />
        <!-- 
        <link rel="stylesheet" href="="<?php echo base_url();?>estilo/css/style2.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="="<?php echo base_url();?>estilo/css/style3.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="="<?php echo base_url();?>estilo/css/style4.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="="<?php echo base_url();?>estilo/css/style5.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="="<?php echo base_url();?>estilo/css/style6.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="="<?php echo base_url();?>estilo/css/style7.css" type="text/css" media="screen" />
         -->
        <link rel="stylesheet" href="<?php echo base_url();?>estilo/css/jquery-ui.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url();?>estilo/css/jquery.wysiwyg.css" type="text/css" media="screen" />        
        <!--Internet Explorer Trancparency fix-->
        <!--[if IE 6]>
        <script src="js/ie6pngfix.js"></script>
        <script>
          DD_belatedPNG.fix('#head, a, a span, img, .message p, .click_to_close, .ie6fix');
        </script>
        <![endif]-->         
        <script type='text/javascript' src='<?php echo base_url();?>estilo/js/jquery.js'></script>
        <script type='text/javascript' src='<?php echo base_url();?>estilo/js/jquery-ui.js'></script>
        <script type='text/javascript' src='<?php echo base_url();?>estilo/js/jquery.wysiwyg.js'></script>
        <script type='text/javascript' src='<?php echo base_url();?>estilo/js/custom.js'></script>
    </head>
    
    <body class="nobackground" >
    	
        <div id="login">
        
        	
            <div class="icon_login ie6fix"></div>
                
        	<form id="login-form" action="<?php echo site_url('login/patente_regional');?>" method="post">
            <p>
            	<label for="name">Nombre de Usuario</label>
            	<input class="input-medium" type="text" value="" name="usuario" id="usuario"/>
        	</p>
        	<p>
            	<label for="password">Clave de Usuario</label>
            	<input class="input-medium" type="password" value="" name="password" id="password"/>
        	</p>

        	<p>
            	<input class="button" name="submit" type="submit" value="Aceptar"/>
        	</p>
            </form>
        </div>
        <?php if (!empty($mensaje_error)){?>
        <div class="login_message message error">
          <p>Nombre o clave de Usuario incorrecta.
		  </p>
        </div>
		<?php }?>	
       
    </body>
</html>