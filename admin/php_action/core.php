<?php 

session_start();

require_once 'includes/conn.php';

// echo $_SESSION['userId'];

if(!$_SESSION['userId']) {
	header('location: http://localhost/mystockcontrol/index.php');	
} 



?>