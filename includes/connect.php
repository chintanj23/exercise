<?php  
session_start();
error_reporting(((E_ALL | E_STRICT) ^ E_NOTICE ^ E_DEPRECATED));
define("ABSOLUTE_PATH", dirname(__DIR__) . "/");
 

$_DBC = array(
  'server' => 'localhost',
  'user' => 'root',
  'pass' => '',
  'database' => "joomdev"
);
 
include ABSOLUTE_PATH . '/includes/pdo_operation.php'; 
include ABSOLUTE_PATH . '/includes/pagination.php'; 

$pdo = new PdoOpt(); 


$_tmp_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$HOST = "http://" .  $_tmp_host  . "/joomdev"; 


function redirect($url) {
    header('Location: '.$url);
    die();
}

function makeSafe($string) {
  if (!is_array($string)) {
    $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
  }
  return $string;
}

?>