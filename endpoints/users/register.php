<?php 
	
$registerData = json_decode(file_get_contents('php://input'), true);

$username = $registerData["username"];
$name = $registerData["name"];
$email = $registerData["email"];
$role = $registerData["role"];
$facultyNumber = $registerData["facultyNumber"];
$password = $registerData["password"];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

require_once "../../php/User.php";

$user = new User($username, $name, $email, $role, $facultyNumber, $hashedPassword);
$result = $user->persistUser();

echo json_encode($result);

?>