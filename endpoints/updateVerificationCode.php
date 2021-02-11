<?php

session_start();
if (!isset($_SESSION['username'])) {
	echo json_encode([
		'status' => false,
		'message' => 'User not logged'
	]);
} else {
    $verification = json_decode(file_get_contents('php://input'), true);
    
    $verificationCode = $verification['code'];
    $username = $_SESSION['username'];

    require_once ".././php/DatabaseConnection.php";

	$databaseHandle = new DatabaseConnection();
    $connection = $databaseHandle->getConnection();
    
    $updateCodeStatement = "UPDATE `users` SET `verificationCode` = :verificationCode WHERE `users`.`username` = :username";
    $prepareStatement = $connection->prepare($updateCodeStatement);
    $result = $prepareStatement->execute([
        'verificationCode' => $verificationCode,
        'username' => $username
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

?>