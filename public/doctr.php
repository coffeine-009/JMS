<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    //realpath(APPLICATION_PATH . '/../library'),
	realpath(APPLICATION_PATH . '/../docs/doctrine_test/Doctrine-1.2.4'),
    get_include_path(),
)));



require_once( 'Doctrine.php' );

spl_autoload_register( 
	array(
		'Doctrine', 
		'autoload'
	) 
);


$manager = Doctrine_Manager :: getInstance();

$link = Doctrine_Manager :: connection(
	'mysql://root:developer@localhost/jms', 
	'doctrine'
);

/*
$dbs = $link -> import -> listDatabases();

echo '<pre>', 
print_r( $dbs, 1 ), 
'</echo>';
*/

//- Generate models -//
Doctrine :: generateModelsFromDb( 
	//'/home/vitaliy/Projects/Web/jms/www/JMS/docs/models', 
	'models', 
	array(
		'doctrine'
	), 
	array(
		'classPrefix' 		=> 'Jms_', 
		'classPrefixFiles' 	=> false, 
	)
);
?>
