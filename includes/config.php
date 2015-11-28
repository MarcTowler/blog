<?php
ob_start();
session_start();

//database credentials
define('DBHOST','');
define('DBUSER','');
define('DBPASS','');
define('DBNAME','');
define('PORT'  ,'3306');

define('SITENAME',    'Sample Name');
define('URL',         'https://www.example.com/'); //leave trailing /
define('DESCRIPTION', 'YOUR DESCRIPTION');

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