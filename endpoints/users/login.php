<?php 

session_start();

$user = json_decode(file_get_contents('php://input'), true);
if (isset($_SESSION['username'])) {
    echo json_encode([
        'status' => true,
        'username' => $_SESSION['username'],
    ]);
} else {
	$username = $user['username'];
	$password = $user['password'];

	require_once "../.././php/DatabaseConnection.php";

	$databaseHandle = new DatabaseConnection();
	$connection = $databaseHandle->getConnection();

	$selectStatement = $connection->prepare("SELECT password FROM `users` WHERE username = :username");
	$result = $selectStatement->execute([
		'username' => $username
	]);
	$databaseUser = $selectStatement->fetch();

	if (!password_verify($password, $databaseUser['password'])) {
		echo json_encode([
			'status' => false
		]);
	} else {
		$_SESSION['username'] = $username;

		echo json_encode([
			'status' => true,
			'username' => $_SESSION['username']
		]);
	}
}

?>