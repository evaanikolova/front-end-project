<?php 

session_start();
if (!isset($_SESSION['username'])) {
	echo json_encode([
		'status' => false,
		'message' => 'User not logged'
	]);
} else {
	$userPasswords = json_decode(file_get_contents('php://input'), true);

	$username = $_SESSION['username'];
	$password = $userPasswords['current'];
	$newPassword = $userPasswords['new'];

	require_once "../.././php/DatabaseConnection.php";

	$databaseHandle = new DatabaseConnection();
	$connection = $databaseHandle->getConnection();

	$selectPasswordStatement = $connection->prepare("SELECT id, password FROM `users` WHERE username = :username");
	$result = $selectPasswordStatement->execute([
		'username' => $username
	]);
	$databaseUser = $selectPasswordStatement->fetch();

	if (!password_verify($password, $databaseUser['password'])) {
		echo json_encode([
			'status' => false,
			'message' => 'wrong password'
		]);
	} else {
		$updatePasswordStatement = "UPDATE `users` SET `password` = :password WHERE `users`.`id` = :id";
		$prepareStatement = $connection->prepare($updatePasswordStatement);
		$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
		$result = $prepareStatement->execute([
			'password' => $hashedPassword,
			'id' => $databaseUser['id']
		]);

		if ($result) {
			echo json_encode([
				'status' => true
			]);
		} else {
			echo json_encode([
				'status' => false,
				'message' => $updatePasswordStatement->errorInfo()[2]
			]);
		}
	}
}

?>