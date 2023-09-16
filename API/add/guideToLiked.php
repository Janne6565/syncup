<?php

include "../util.php";
include "../database.php";

$conn = connect();

$params = getParams(array("userId", "guideId"));

if (checkAuthed()) {
    $sqlInsert = "INSERT INTO LikedGuides (UserId, GuideId) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ii", $params["userId"], $params["guideId"]);
    $stmtInsert->execute();
    $resultInsert = $stmtInsert->get_result();
} else {
    throwCode("Not authorized", 401, "Not authorized");
}
