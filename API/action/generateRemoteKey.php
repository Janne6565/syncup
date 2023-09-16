<?php

include '../database.php';
include '../util.php';

if (checkAuthed()) {
    $params = getParams(array("userId"));

    $randomKey = generateRandomString(32);
    $insert = "INSERT INTO RemoteKeys (UserId, RemoteKey, ExpirationDate) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 DAY))";
    $conn = connect();
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("is", $params["userId"], $randomKey);
    $stmt->execute();

    echo json_encode(array("remoteKey" => $randomKey, "success" => "Remote key generated", "code" => 200));
} else {
    throwCode("Unauthorized", 401, "Unauthorized");
}