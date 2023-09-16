<?php

include "../util.php";
include "../database.php";

$conn = connect();

if (checkAuthed()) {
    $sql = "DELETE FROM LikedLineups WHERE LikedLineups.UserId = ? AND LikedLineups.LineupId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $params["userId"], $params["lineupId"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        throwCode("Not authorized", 401, "Not authorized");
    } else {
        echo json_encode(array("success" => true));
    }
} else {
    throwCode("Not authorized", 401, "Not authorized");
}