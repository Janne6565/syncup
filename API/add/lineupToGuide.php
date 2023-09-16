<?php

include "../util.php";
include "../database.php";

$conn = connect();

$params = getParams(array("userId", "guideId"));

if (checkAuthed()) {
    $sqlCheck = "SELECT * FROM Guides WHERE Guides.Id = ? AND Guides.UserId = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $params["guideId"], $params["userId"]);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    if ($resultCheck->num_rows > 0) {
        $sqlInsert = "INSERT INTO GuideLineups (GuideId, LineupId, Hirarchy) VALUES (?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("iii", $params["guideId"], $params["lineupId"], $params["hirarchy"]);
        $stmtInsert->execute();
        $resultInsert = $stmtInsert->get_result();
        echo json_encode(array("success" => true));
    } else {
        throwCode("Not authorized", 401, "Not authorized");
    }
} else {
    throwCode("Not authorized", 401, "Not authorized");
}
