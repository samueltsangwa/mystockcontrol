<?php 
	session_start();  // Ensure session is started
	include 'includes/conn.php';
	include 'includes/session.php'; 
 	include 'includes/header.php';
	 include 'includes/navbar.php'; 
	 if (isset($_SESSION['username'])) {
		echo "Hello, " . $_SESSION['username'];  // Test if username is set
	} else {
		echo "Session data is not available.";  // Debugging message
	}
	?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	     	
	</body>
</html>