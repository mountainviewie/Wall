<?php

session_start();
require('new-connection.php');

// var_dump($_SESSION);



?>

<html>
<head>
	<title>Coding Dojo Wall</title>
	<style type="text/css">
		header {
			border-bottom:solid 2px black;
		}
		h4, h1, a {
			display: inline-block;
			vertical-align: top;
		}
		h4 {
			/*float:right;*/
			margin-left:400px
		}
		a {
			float: right;
		}
		textarea {
			overflow:scroll;
		}

		.message {
			border-top:black solid 1px;
		}
		.comment {
			margin-left:20px;
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
		<a href="wall_process.php">LOG OFF</a>
		<h4>Welcome, <?= $_SESSION['first_name'] ?>!</h4>
	</header>
	<h3>Post a message</h3>
	<form action="wall_process.php" method="post">
		<textarea name="content" rows="4" cols="50"></textarea>
		<input type="submit" value="Post a message">
		<input type="hidden" name="action" value="message">
	</form>
	<?php
		// Query to find all messages
		$query = "SELECT users.first_name, users.last_name, DATE_FORMAT(messages.created_at, '%M %D %Y'), messages.message
				FROM users JOIN messages ON users.id = messages.user_id;";
		$message = fetch_all($query);

		// Query to find all comments
		$query = "SELECT comments.message_id, users.first_name, users.last_name, DATE_FORMAT(comments.created_at, '%M %D %Y'), comments.comment
				FROM users JOIN comments ON users.id = comments.user_id;";
		$comment = fetch_all($query);
		
		// Loop to display all messages & comments
		for ($i=0; $i<count($message); $i++) {
			echo "<div class='message'> <h5>". $message[$i]['first_name'] . " " . $message[$i]['last_name'] . " - " .
				 $message[$i]["DATE_FORMAT(messages.created_at, '%M %D %Y')"] . "</h5><p>" .
				 $message[$i]['message'] . "</p> </div>";

			foreach ($comment as $key => $value) {
				if ($value['message_id'] == ($i +1)) {
					echo "<div class='comment'><h5>". $value['first_name'] . " " . $value['last_name'] . " - " .
						 $value["DATE_FORMAT(comments.created_at, '%M %D %Y')"]. "</h5><p>" .
						 $value['comment']. "</p></div>";
				}
			}
				
			// for ($j=0;$j<count($comment);$j++


			?>
		<!-- To display comment box below messages -->
			<h5>Post a comment</h5>
			<form action="wall_process.php" method="post">
				<textarea name="content" rows="4" cols="50"></textarea>
				<input type="submit" value="Post a comment">
				<input type="hidden" name="message_number" value=<?= "$i" ?>>
				<input type="hidden" name="action" value="comment">
			</form>
			<?php
		}
	?>
</body>
</html>