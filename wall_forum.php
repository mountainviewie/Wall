<?php

session_start();
require('new-connection.php');

var_dump($_SESSION);



?>

<html>
<head>
	<title>Coding Dojo Wall</title>
	<style type="text/css">
		header {
			border-bottom:solid 2px black;
		}
		textarea {
			overflow:scroll;
		}

	</style>
</head>
<body>
	<?php 
	if (isset($_SESSION['success'])) {
		echo "<h5>".$_SESSION['success']."</h5>";
	}

	?>
	<header>
		<h1>Coding Dojo Wall</h1>
		<h4>Welcome, <?= $_SESSION['first_name'] ?>!</h4>
		<a href="wall_process.php">LOG OFF</a>
	</header>
	<h3>Post a message</h3>
	<form action="wall_process.php" method="post">
		<textarea name="content" rows="4" cols="50"></textarea>
		<input type="submit" value="Post a message">
		<input type="hidden" name="action" value="message">
	</form>
	<?php

		// foreach ($_SESSION['messages'] as $key => $content) {
		// 	echo $content;
		// }


	?>
</body>
</html>