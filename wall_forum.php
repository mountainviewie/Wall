<?php

session_start();
require('new-connection.php');




?>

<html>
<head>
	<meta charset="utf-8">
	<title>Coding Dojo Wall</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
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
		.delete, h5 {
			display: inline-block;
		}
		.delete {
			margin-left:300px;
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
		<textarea name="content" rows="4" cols="75"></textarea>
		<input type="submit" value="Post a message">
		<input type="hidden" name="action" value="message">
	</form>
	<?php
		// Query to find all messages
		$query = "SELECT messages.user_id, messages.id, users.first_name, users.last_name, DATE_FORMAT(messages.created_at, '%M %D %Y'), messages.message
				FROM users JOIN messages ON users.id = messages.user_id ORDER BY messages.created_at ASC;";
		$message = fetch_all($query);

		// Query to find all comments
		$query = "SELECT comments.user_id, comments.id, comments.message_id, users.first_name, users.last_name, comments.created_at, DATE_FORMAT(comments.created_at, '%M %D %Y'), comments.comment
				FROM users JOIN comments ON users.id = comments.user_id ORDER BY comments.created_at ASC;";
		$comment = fetch_all($query);

		// echo "<h1>SESSION:</h1>";
		// var_dump($_SESSION);
		// echo "<h1>POST:</h1>";
		// var_dump($_POST);
		// echo "<h1>Messages:</h1>";
		// var_dump($message);
		// echo "<h1>Comments:</h1>";
		// var_dump($comment);

		// Loop to display all messages & comments
		for ($i=0; $i<count($message); $i++) {
			echo "<div class='message'> <h5>". $message[$i]['first_name'] . " " . $message[$i]['last_name'] . " - " .
				 $message[$i]["DATE_FORMAT(messages.created_at, '%M %D %Y')"] . "</h5>";
			// Allow user to delete own messages
			if ($message[$i]['user_id'] == $_SESSION['id']) { ?>
					<form class="delete" action="wall_process.php" method="post">
						<input type="submit" value="Delete">
						<input type="hidden" name="action" value="delete_message">
						<input type="hidden" name="message_number" value=<?= '"' . $message[$i]['id'] . '"' ?>>
					</form>
				
				<?php
				}
			echo "<p>" . $message[$i]['message'] . "</p> </div>";

			// Check each message for comments
			foreach ($comment as $key => $value) {
				if ($value['message_id'] == ($message[$i]['id'])) {
					echo "<div class='comment'><h5>". $value['first_name'] . " " . $value['last_name'] . " - " .
						 $value["DATE_FORMAT(comments.created_at, '%M %D %Y')"]. "</h5>";
					// Allow user to delete own comments
					if (($value['user_id'] == $_SESSION['id'])) { ?>
						<form class="delete" action="wall_process.php" method="post">
							<input type="submit" value="Delete">
							<input type="hidden" name="action" value="delete_comment">
							<input type="hidden" name="comment_number" value= <?= '"' . $value['id']. '"' ?> >
						</form>

					<?php
					}
					echo "<p>" . $value['comment']. "</p></div>";
				}
			}


			?>
		<!-- To display comment box below messages -->
			<h5>Post a comment</h5>
			<form action="wall_process.php" method="post">
				<textarea name="content" rows="4" cols="75"></textarea>
				<input type="submit" value="Post a comment">
				<input type="hidden" name="message_number" value=<?= $message[$i]['id'] ?>>
				<input type="hidden" name="action" value="comment">
			</form>
			<?php
		}
	?>
</body>
</html>