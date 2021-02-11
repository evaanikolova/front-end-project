<?php
    $eventData = json_decode(file_get_contents('php://input'), true);

    $name = $eventData["name"];
    $date = $eventData["date"];
    $start_hour = $eventData["start_hour"];
    $end_hour = $eventData["end_hour"];
    $period = $eventData["period"];

    require_once '../php/DatabaseConnection.php';

    $dbCon = new DatabaseConnection();

    $con = $dbCon->getConnection();

    $sqlInsertStatement = "INSERT INTO `slots`(`id`, `name`, `date`, `start_hour`, `end_hour`, `period`) VALUES (NULL, :name, :date, :start_hour, :end_hour, :period)";
    $prepareStatement = $con->prepare($sqlInsertStatement);
    $result = $prepareStatement->execute([
        'name' => $name,
        'date' => $date,
        'start_hour' => $start_hour,
        'end_hour' => $end_hour,
        'period' => $period
    ]);

    if ($result) {
        $response = array(
            "status" => true,
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