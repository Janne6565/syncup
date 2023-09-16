<?php

include "../util.php";
include "../database.php";

$conn = connect();

$params = getParams(array("userId", "guideId"));

if (checkAuthed()) {
    $sql = "DELETE FROM Guides WHERE Guides.Id = ? AND Guides.UserId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $params["guideId"], $params["userId"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        throwCode("Not authorized", 401, "Not authorized");
    } else {
        $sql = "DELETE FROM GuideLineups WHERE GuideLineups.GuideId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $params["guideId"]);
        $stmt->execute();
        $result = $stmt->get_result();
        echo json_encode(array("success" => true));
    }
} else {
    throwCode("Not authorized", 401, "Not authorized");
}
