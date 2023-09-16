<?php

include '../database.php';
include '../util.php';

$params = getParams(array("command", "userId", "remoteKey"));

$sql = "SELECT * FROM RemoteKeys WHERE RemoteKey = ? AND UserId = ? AND ExpirationDate > NOW()";
$conn = connect();
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $params["remoteKey"], $params["userId"]);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    echo json_encode(array("error" => "Invalid remote key"));
    exit();
} else {
    $insertSql = "INSERT INTO RemoteCommands (RemoteKeyId, Command, TimeSend) VALUES (?, ?, NOW())";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("is", $res->fetch_assoc()["ID"], $params["command"]);
    $insertStmt->execute();

    echo json_encode(array("success" => "Command sent"));
    exit();
}