<?php

session_start();
require('new-connection.php');


/** BEGIN BUTTON PRESS **/

if(isset($_POST['action']) && $_POST['action'] == 'login') {
	user_login($_POST);

} 
elseif(isset($_POST['action']) && $_POST['action'] == 'register') {
	user_registration($_POST);

} 
elseif(isset($_POST['action']) && $_POST['action'] == 'message') {
	post_message($_POST);
	header('Location: wall_forum.php');
} 
elseif(isset($_POST['action']) && $_POST['action'] == 'comment') {
	// var_dump($_POST);
	// die();
	post_comment($_POST);
	header('Location: wall_forum.php');
} 
elseif(isset($_POST['action']) && $_POST['action'] == 'delete_message') {
	delete_message($_POST);
	header('Location: wall_forum.php');
} 
elseif(isset($_POST['action']) && $_POST['action'] == 'delete_comment') {
	delete_comment($_POST);
	header('Location: wall_forum.php');
} 
else { // Malicious attempts
	session_destroy();
	header('Location: wall_login.php');
	die();
}


/** BEGIN POST VALIDATION **/

function user_registration($post) {

	$_SESSION['errors'] = [];

	if (empty($_POST['first_name'])) {
		$_SESSION['errors'][] = "First name is required!";
	}
	if (empty($_POST['last_name'])) {
		$_SESSION['errors'][] = "Last name is required!";
	}
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$_SESSION['errors'][] = "Please enter a valid email!";
	}
	if (empty($_POST['password'])) {
		$_SESSION['errors'][] = "Password field is required!";
	}
	if (empty($_POST['confirm_password'])) {
		$_SESSION['errors'][] = "Password fields must match!";
	}
	if (count($_SESSION['errors']) > 0) {
		header('Location: wall_login.php');
	} else {
		$esc_first_name = escape_this_string($_POST['first_name']);
		$esc_last_name = escape_this_string($_POST['last_name']);
		$esc_email = escape_this_string($_POST['email']);
		$esc_password = escape_this_string($_POST['password']);

		//insert new user into database
		$query = "INSERT INTO wall.users (first_name, last_name, email, password, created_at, updated_at)
		 	VALUES ('{$esc_first_name}', '{$esc_last_name}', '{$esc_email}', '{$esc_password}', NOW(), NOW())  ";
		run_mysql_query($query);

		//retrieve user id
		$query = "SELECT id FROM users WHERE email = '{$_POST['email']}' ";
		$id = run_mysql_query($query);
		$_SESSION['id'] = $id;

		$_SESSION['success'] = "User successfully created!";
		$_SESSION['first_name'] = $_POST['first_name'];
		$_SESSION['last_name'] = $_POST['last_name'];
		header('Location: wall_forum.php');
	}
}


/** BEGIN USER LOGIN **/

function user_login($post) {
	$query = "SELECT * FROM users WHERE email = '{$_POST['email']}' AND password = '{$_POST['password']}' ";
	$user = fetch_all($query);
	if (count($user) > 0) {
		$_SESSION['id'] = $user[0]['id'];
		$_SESSION['first_name'] = $user[0]['first_name'];
		$_SESSION['last_name'] = $user[0]['last_name'];
		$_SESSION['logged_in'] = TRUE;
		header('Location: wall_forum.php');
	}
}


/** BEGIN MESSAGE POSTING **/

function post_message($post) {

	// insert message into database
	$query = "INSERT INTO messages (user_id, message, created_at, updated_at) 
			VALUES ('{$_SESSION['id']}', '{$_POST['content']}', NOW(), NOW()) ";
	// echo $query;		
	run_mysql_query($query);
	// die();
}

/** BEGIN MESSAGE DELETION **/

function delete_message($post) {
	$query = "DELETE FROM comments WHERE comments.message_id = '{$_POST['message_number']}';";
	run_mysql_query($query);
	$query = "DELETE FROM messages WHERE messages.id = '{$_POST['message_number']}';";
	run_mysql_query($query);
}



/** BEGIN COMMENT POSTING **/

function post_comment($post) {
	$number = ($_POST['message_number']);

	// insert post into database
	$query = "INSERT INTO comments (message_id, user_id, comment, created_at, updated_at) 
			VALUES ('{$number}', '{$_SESSION['id']}', '{$_POST['content']}', NOW(), NOW()) ";
	run_mysql_query($query);
}


/** BEGIN COMMENT DELETION **/

function delete_comment($post) {
	$query = "DELETE FROM comments WHERE comments.id = '{$_POST['comment_number']}';";
	run_mysql_query($query);
}


?>









