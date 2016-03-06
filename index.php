<?php include('autoload.php');

/** uncomment if you want to make your website completely private **/
/**/ if(!checkLogged()) redirect(BASE_URL . 'login.php'); /**/

$_PATH = route();

$class = dispatch();
	
/** output **/	
if($class->ajax)
	echo $class->output;
else	
	echo tpl('index', array(
		'content' 	=> $class->output,
		'class'		=> $class
		)
	);		
