<?php

$email = $_POST['email'],
$password = $_POST['password'],
$phone = $_POST['phone'],
$about = $_POST['about'],
$note = $_POST['note'];

if( is_auth() ) {
	set_email( $_SESSION['student_id'], $email );
	set_password( $_SESSION['student_id'], $password );
	set_phone( $_SESSION['student_id'], $phone );
	set_about( $_SESSION['student_id'], $about );
	set_note( $_SESSION['student_id'], $note );
}

?>