<?php
ob_start();
session_start();

//database credentials
define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','06061990');
define('DBNAME','c1blog');
define('PORT'  ,'3306');

define('SITENAME',    'Sample Name');
define('URL',         'http://localhost/'); //leave trailing /
define('DESCRIPTION', 'Website Development and Design blog written by Marc Towler, includes game
                    reviews and book reviews');

$db = new PDO("mysql:host=".DBHOST.";port=".PORT.";dbname=".DBNAME, DBUSER, DBPASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//set timezone
date_default_timezone_set('Europe/London');
//load classes as needed

function __autoload($class) {

    $class = strtolower($class);
    //if call from within assets adjust the path
    $classpath = 'classes/class.'.$class . '.php';
    if ( file_exists($classpath)) {
        require_once $classpath;
    }

    //if call from within admin adjust the path
    $classpath = '../classes/class.'.$class . '.php';
    if ( file_exists($classpath)) {
        require_once $classpath;
    }

    //if call from within admin adjust the path
    $classpath = '../../classes/class.'.$class . '.php';
    if ( file_exists($classpath)) {
        require_once $classpath;
    }

}
$user = new User($db);

$seo = false;

include_once('functions.php');
?>