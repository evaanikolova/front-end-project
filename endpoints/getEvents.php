<?php

    require_once '../php/DatabaseConnection.php';

    $dbCon = new DatabaseConnection();

    $con = $dbCon->getConnection();

    $sqlSelectStatement = "SELECT name, fn_number, subject, description, presentation_url, meeting_url, CONVERT_TZ(start, '+00:00', '+03:00') as start, CONVERT_TZ(end, '+00:00', '+03:00') as end FROM `events` JOIN `users` ON events.username = users.username ORDER BY start ASC";
    $prepareStatement = $con->prepare($sqlSelectStatement);
    $result = $prepareStatement->execute([]);

    // var_dump($result);

    $databaseEvents = $prepareStatement->fetchAll();

    if ($result) {
        $response = array(
            "status" => true,
            "events" => $databaseEvents
        );

        echo json_encode($response);
    } else {
        $error = $prepareStatement->errorInfo();
        $response = array(
            "status" => false,
            "message" => "There was an error saving your data to the database: " . $error[2],
            "errorCode" => $error[0]
        );

        echo json_encode($response);
    }
?>