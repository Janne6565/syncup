<?php

include "../util.php";
include "../database.php";

$conn = connect();

$params = getParams(array("userId", "lineupId"));

if (checkAuthed()) {
    $sqlInsert = "INSERT INTO LikedLineups (UserId, LineupId) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ii", $params["userId"], $params["lineupId"]);
    $stmtInsert->execute();
    $resultInsert = $stmtInsert->get_result();
} else {
    throwCode("Not authorized", 401, "Not authorized");
}
