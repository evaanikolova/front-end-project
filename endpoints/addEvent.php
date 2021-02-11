<?php
    $eventData = json_decode(file_get_contents('php://input'), true);

    // var_dump($event);
    $username = $eventData["username"];
    $subject = $eventData["subject"];
    $start = $eventData["start"];
    $end = $eventData["end"];
    $description = $eventData["description"];
    $presentation_url = $eventData["presentation_url"];
    $meeting_url = $eventData["meeting_url"];
    $fn_number = $eventData["fn_number"];

    require_once '../php/DatabaseConnection.php';

    $dbCon = new DatabaseConnection();

    $con = $dbCon->getConnection();

    $sqlInsertStatement = "INSERT INTO `events` (`id`, `username`, `fn_number`, `subject`, `description`, `presentation_url`, `meeting_url`, `start`, `end`) VALUES (NULL, :username, :fn_number, :subject, :description, :presentation_url, :meeting_url, :start, :end)";
    $prepareStatement = $con->prepare($sqlInsertStatement);
    $result = $prepareStatement->execute([
        'username' => $username,
        'fn_number' => $fn_number,
        'subject' => $subject,
        'description' => $description,
        'presentation_url' => $presentation_url,
        'meeting_url' => $meeting_url,
        'start' => $start,
        'end' => $end
    ]);

    // var_dump($start);

    if ($result) {
        $response = array(
            "status" => true,
            "time_start" => $start,
            "time_end" => $end
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