<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/*
require_once 'Coffeine/FileSystem/PathManager.php';

$pm = new \FileSystem\PathManager( '    /downloads' );
$pm -> Forward( 'backup.zip' );

echo $pm -> Get();
 */
//require_once 'Coffeine/FileSystem/FileManager.php';
//
//$fm = new \FileSystem\FileManager();


require_once 'Coffeine/FileSystem/File.php';
$f = new FileSystem\File( '/home/vitaliy/Projects/Web/JMS/tmp/dsc' );
var_dump($f->GetCreation()); 
//echo var_dump($f->Create('r'));
//echo var_dump($f ->Copy('/home/vitaliy/Projects/Web/JMS/tmp/dsc'));
//echo var_dump($f ->Move('/home/vitaliy/Projects/Web/JMS/tmp/dsc'));
//echo var_dump($f ->Delete());
//echo var_dump($f -> GetContent());
//$f ->SetContent('ok');

