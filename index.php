<?php
define("MODUL", "modules/");
define("LIB", "lib/");
define("TEMPLATE", "template/");
define("ASET", "assets/");

session_start();
include_once("config/load.php");

$config['apps'] = isset($_GET['apps']) ? $_GET['apps'] : '';
$config['module'] = isset($_GET['module']) ? $_GET['module'] : 'login';
$config['page'] = isset($_GET['page']) ? "/". $_GET['page'] : '/index';

include_once(LIB . "core/load.php");
if(file_exists(MODUL . $config['module'] . $config['page'] . ".php"))
 include_once(MODUL . $config['module'] . $config['page'] . ".php");
else
{
 $_SESSION['error_page'] = "404";
 redirect(module_url('error_page'));
}
 
if($config['apps'] !== '')
 include_once(TEMPLATE . $config['apps'] . "/load.php");