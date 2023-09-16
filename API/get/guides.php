<?php

include '../util.php';
include '../database.php';

$conn = connect();

$params = getParams(array("mapId"));

$sql = "SELECT * FROM Guides WHERE Guides.MapId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $params["mapId"]);
$stmt->execute();
$result = $stmt->get_result();
$guides = array();

while ($row = $result->fetch_assoc()) {
    $guides[] = $row;
    $sqlForeach = "SELECT * FROM GuideLineups WHERE GuideLineups.GuideId = ?";
    $stmtForeach = $conn->prepare($sqlForeach);
    $stmtForeach->bind_param("i", $row["Id"]);
    $stmtForeach->execute();
    $resultForeach = $stmtForeach->get_result();
    $lineups = array();
    while ($rowForeach = $resultForeach->fetch_assoc()) {
        $lineups[] = $rowForeach;
    }
    $guides[count($guides) - 1]["Lineups"] = $lineups;
}

throwCode(json_encode(array("guides"=>$guides)), 200, "Success");