<?php

session_start();
if (!isset($_SESSION['username'])) {
	echo json_encode([
		'status' => false,
		'message' => 'User not logged'
	]);
} else {
    $delayObject = json_decode(file_get_contents('php://input'), true);

    $date = $delayObject['date'];
    $delay = $delayObject['delay'];
    $formattedDate = explode('-', $date)[0] . '-' . explode('-', $date)[1] . '-'.  explode('-', $date)[2];

    require_once ".././php/DatabaseConnection.php";

	$databaseHandle = new DatabaseConnection();
    $connection = $databaseHandle->getConnection();
    
    $updateDelayStatement = "UPDATE `slots` SET `delay` = :delay WHERE `slots`.`date` = :date";
    $prepareStatement = $connection->prepare($updateDelayStatement);
    $result = $prepareStatement->execute([
        'delay' => $delay,
        'date' => $formattedDate
    ]);

    if ($result) {
        echo json_encode([
            'status' => true
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => $updateDelayStatement->errorInfo()[2]
        ]);
    }
}

?>