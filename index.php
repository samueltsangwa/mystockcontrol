<?php
	session_start(); 
  	include 'includes/session.php';
	include 'includes/conn.php';

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$username = $_POST['username'];
		$password = $_POST['password'];
	
		// Fetch user data
		$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
		$stmt->bind_param("ss", $username, $username); // Check both username and email
		$stmt->execute();
		$result = $stmt->get_result();
	
		if ($result->num_rows > 0) {
			$user = $result->fetch_assoc();
	
			// Verify password
			if (password_verify($password, $user['password'])) {
				// Password is correct; start session and set session variables
				session_start();  // Ensure session is started before setting session variables
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['username'] = $user['username'];  // Store username in session
				$_SESSION['profile_photo'] = $user['profile_photo'] ?? 'path/to/default/profile.jpg';  // Store profile photo in session
				
				header("Location: home.php");
				exit();
			} else {
				$_SESSION['error_message'] = "Invalid password.";
			}
		} else {
			$_SESSION['error_message'] = "User not found.";
		}
	
		$stmt->close();
	}
	
	// Display error message
	if (isset($_SESSION['error_message'])): ?>
		<div class='alert alert-danger text-center' id="message-alert">
			<?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
		</div>
	<?php endif;
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
  	<div class="login-logo">
  		<b>Stock Control Sysytem</b></br>
		<b>Sales Panel</b></br>
  	</div>
  
  	<div class="login-box-body">
    	<p class="login-box-msg">Sign in </p>

		<img src="logo.png" alt="Logo" class="center-logo" width="30%">
			  <div class="panel-body"> 

    	<form action="home.php" method="POST">
      		<div class="form-group has-feedback">
        		<input type="text" class="form-control" name="username" placeholder="Username" required>
        		<span class="glyphicon glyphicon-user form-control-feedback"></span>
      		</div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
		 
		  <div class="row">
    <div class="col-xs-6">
        <button type="submit" class="btn btn-primary btn-block btn-flat" name="login"><i class="fa fa-sign-in"></i> Sign In</button>
    </div>
</div>
    	</form>
  	</div>
  	<?php
  		if(isset($_SESSION['error'])){
  			echo "
  				<div class='callout callout-danger text-center mt20'>
			  		<p>".$_SESSION['error']."</p> 
			  	</div>
  			";
  			unset($_SESSION['error']);
  		}
  	?>
</div>
	
<?php include 'includes/scripts.php' ?>
<style>
		.center-logo {
			display: block;
			margin-left: auto;
			margin-right: auto;
			padding: 0px;
		}
	</style>
</body>

<footer>

</footer>
</div>
</html>