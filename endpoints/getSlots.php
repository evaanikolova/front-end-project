<?php

    require_once '../php/DatabaseConnection.php';

    $dbCon = new DatabaseConnection();

    $con = $dbCon->getConnection();

    $sqlSelectStatement = "SELECT * FROM `slots`";
    $prepareStatement = $con->prepare($sqlSelectStatement);
    $result = $prepareStatement->execute([]);

    // var_dump($result);

    $databaseSlots = $prepareStatement->fetchAll();

    if ($result) {
        $response = array(
            "status" => true,
            "slots" => $databaseSlots
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