<?php

include '../util.php';
include '../database.php';

$conn = connect();

$params = getParams(array("userId", "name", "description", "lineups", "mapId"));

if (checkAuthed()) {

    $sql = "INSERT INTO Guides (Name, Description, UserId, MapId) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $params["name"], $params["description"], $params["userId"], $params["mapId"]);
    $stmt->execute();

    $guideId = $conn->insert_id;
    
    $lineups = $params["lineups"]->explode(",");


    $count = 0;
    foreach ($lineups as $lineup) {
        $sql = "INSERT INTO GuideLineups (GuideId, LineupId, Hirarchy) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $guideId, $lineup, $count);
        $stmt->execute();
        $count++;
    }

    echo json_encode(array("success" => true, "id" => $guideId));
}
