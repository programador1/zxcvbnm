<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
<?php 
//--INICIO
//Agregar este codigo en el head de la plantilla
if(!empty($css_files)){
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; }
//----------------------FIN 
?>
<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>
</head>
<body>
	<div> 
            
                <!--  MENU copiar en el menu de la plantilla-->
		<a href='<?php echo site_url('administrador/Carrera')?>'>Carrera</a> |
		<a href='<?php echo site_url('administrador/Pais')?>'>Pais</a> |
		<a href='<?php echo site_url('administrador/Banco')?>'>Banco</a> |
		<a href='<?php echo site_url('administrador/GradoAcademico')?>'>Grado Academico</a> | 
		<a href='<?php echo site_url('administrador/Idioma')?>'>Idioma</a> |		 
		<a href='<?php echo site_url('administrador/Universidad')?>'>Universidad</a>
                
	</div>
	<div style='height:20px;'></div>  
    <div>
		<?php 
                // copiar en el lugar donde se desplega el contenido de la plantilla
                echo $output; 
                ?>
    </div>
</body>
</html>
