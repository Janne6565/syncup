<?php

include '../database.php';
include '../util.php';

if (checkAuthed()) {
    $params = getParams(array("userId", "authKey", "timeSinceLastCheck"));

    $sql = "SELECT * FROM RemoteCommands WHERE UserId = ? AND TimeSend > ?";

    $conn = connect();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $params["userId"], $params["timeSinceLastCheck"]);
    $stmt->execute();
    $res = $stmt->get_result();

    $commands = array();
    foreach ($res as $row) {
        $commands[] = $row;
    }

    throwCode("Success", 200, "Success", array("commands" => $commands));
} else {
    throwCode("Unauthorized", 401, "Unauthorized");
}