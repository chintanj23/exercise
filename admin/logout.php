<?php 
include_once __DIR__ . '/includes/connect.php';

 

unset($_SESSION['admin']);

redirect($HOST."/index.php");
?>