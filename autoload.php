<?php 
define('BASE_PATH', dirname(__FILE__) . '/');
define('SITE_PATH', BASE_PATH . 'site/');
define('DB_TYPE', 'mysql'); 

session_name("engine");
session_start(); 

require_once(BASE_PATH . 'data/settings.php');
require_once(BASE_PATH . "engine/functions/functions.php");
require_once(BASE_PATH . "engine/db/db.query.class.php");
require_once(BASE_PATH . "engine/db/db." . DB_TYPE . ".class.php");
require_once(BASE_PATH . "engine/db/db." . DB_TYPE . ".functions.php");
DBconnect();
installationCheck();
require_once(BASE_PATH . 'engine/class.masterclass.php');


getGlobals();
//getLangs();  

