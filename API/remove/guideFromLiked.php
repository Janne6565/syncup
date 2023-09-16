<?php

include "../util.php";
include "../database.php";

$conn = connect();

$params = getParams(array("userId", "guideId"));

if (checkAuthed()) {
    $sql = "DELETE FROM LikedGuides WHERE LikedGuides.GuideId = ? AND LikedGuides.UserId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $params["guideId"], $params["userId"]);
    $stmt->execute();
} else {
    throwCode("Not authorized", 401, "Not authorized");
}