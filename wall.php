<?php







?>




<html>
<head>
	<title>Coding Dojo Wall</title>
</head>
<body>
	<form action="wall_process.php" method="post">
		Email: <input type="text" name="email">
		Password: <input type="text" name="password">
		<input type="submit" value="Login">
		<input type="hidden" name="action" value="login">
	</form>
	<form action="wall_process.php" method="post">
		First Name: <input type="text" name="first_name">
		Last Name: <input type="text" name="last_name">
		Email Address: <input type="text" name="email">
		Password: <input type="password" name="password">
		Confirm Password: <input type="password" name="confirm_password">
		<input type="submit" value="Register">
		<input type="hidden" name="action" value="register">
	</form>
</body>
</html>