<?php 
include_once __DIR__ . '/includes/connect.php';

 

unset($_SESSION['user']);

redirect($HOST."/index.php");
?>