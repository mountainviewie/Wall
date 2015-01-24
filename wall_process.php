<?php

session_start();
require('new-connection.php');


/** BEGIN BUTTON PRESS **/

if(isset($_POST['action']) && $_POST['action'] == 'login') {
	user_login($_POST);

} elseif(isset($_POST['action']) && $_POST['action'] == 'register') {
	user_registration($_POST);

} elseif(isset($_POST['action']) && $_POST['action'] == 'message') {
	$_SESSION['content'] = $_POST['content'];
	post_message($_SESSION);
	header('Location: wall_forum.php');
} elseif(isset($_POST['action']) && $_POST['action'] == 'comment') {
	user_registration($_POST);

} else { // Malicious attempts
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

		//insert new user into database
		$query = "INSERT INTO wall.users (first_name, last_name, email, password, created_at, updated_at)
		 VALUES ('{$_POST['first_name']}', '{$_POST['last_name']}', '{$_POST['email']}', '{$_POST['password']}', NOW(), NOW())  ";
		run_mysql_query($query);

		//retrieve user id
		$id = "SELECT id FROM users WHERE email = '{$_POST['email']}' ";
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

function post_message($session) {
	$_SESSION['messages'][] = "<h5>".$_SESSION['first_name']." ".$_SESSION['last_name'].
		" - ".date('F dS Y')."</h5>".
		"<p>".$_SESSION['content']."</p>";

}



?>