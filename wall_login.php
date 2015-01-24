<?php

session_start();
require('new-connection.php');


// var_dump($_SESSION);
// var_dump($_POST);


?>




<html>
<head>
	<title>Coding Dojo Wall</title>
	<style type="text/css">
		div {
			height:100px;
			text-align: left;
		}
		input {
			display: block;
			margin-left:auto;
			margin-right:auto;
		}
		#login {
			margin-left:350px;
		}
		#forms {
			margin-left:auto;
			margin-right:auto;
			margin-top:auto;
			margin-bottom:auto;
		}
		form {
			width: 250px;
			height: 370px;
			text-align: center;
			display: inline-block;
			vertical-align: top;
			background:linear-gradient(to right, #474747,lightgrey,#474747);
		}
		ul {
			width:210px;
			text-align: center;
			margin-left:auto;
			margin-right:auto;
			margin-bottom:0px;
			color:white;
			background-color:red;
			list-style-type: none;
		}
		li {
			margin-left: -40px;
			text-align: center;
		}
	</style>
</head>
<body>
	<?php
	if (isset($_SESSION['errors'])) {
		echo "<ul>";
		foreach ($_SESSION['errors'] as $error) {
			echo "<li>$error</li>";
		}
		echo "</ul>";
		unset($_SESSION['errors']);
	}
	?>
	<div id="forms">
		<form id="login" action="wall_process.php" method="post">
			<h2>Login</h2>
			Email: <input type="text" name="email"><br>
			Password: <input type="text" name="password"><br>
			<input type="submit" value="Login"><br>
			<input type="hidden" name="action" value="login">
		</form>
		<form action="wall_process.php" method="post">
			<h2>Registration</h2>
			First Name: <input type="text" name="first_name"><br>
			Last Name: <input type="text" name="last_name"><br>
			Email Address: <input type="text" name="email"><br>
			Password: <input type="password" name="password"><br>
			Confirm Password: <input type="password" name="confirm_password"><br>
			<input type="submit" value="Register"><br>
			<input type="hidden" name="action" value="register">
		</form>
	</div>
</body>
</html>