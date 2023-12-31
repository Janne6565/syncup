<?php 
// enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);



include "../util.php";
include "../database.php";

$params = getParams(array("lineupId"));

$sql = "SELECT Lineups.ID, Lineups.UserId, Approved, AbilityId, DateCreated, ImageLineup, ImageStandOn, ImageLandOn, FromSpotId, ToSpotId, Users.Username FROM Lineups, Users WHERE Users.ID = Lineups.UserId AND Lineups.ID = ?";

$conn = connect();

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $params["lineupId"]);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $lineup = $result->fetch_assoc();

    $fromId = $lineup["FromSpotId"];
    $toId = $lineup["ToSpotId"];

    $sqlTo = "SELECT * FROM Spots, Maps WHERE Spots.MapId = Maps.ID AND Spots.ID = ?";
    $stmtTo = $conn->prepare($sqlTo);
    $stmtTo->bind_param("i", $toId);
    $stmtTo->execute();
    $resultTo = $stmtTo->get_result();
    $to = $resultTo->fetch_assoc();

    $sqlFrom = "SELECT * FROM Spots, Maps WHERE Spots.MapId = Maps.ID AND Spots.ID = ?";
    $stmtFrom = $conn->prepare($sqlFrom);
    $stmtFrom->bind_param("i", $fromId);
    $stmtFrom->execute();
    $resultFrom = $stmtFrom->get_result();
    $from = $resultFrom->fetch_assoc();

    $lineup["FromSpot"] = $from;
    $lineup["ToSpot"] = $to;

    $sqlAbility = "SELECT * FROM Abilitys WHERE ID = ?";
    $stmtAbility = $conn->prepare($sqlAbility);
    $stmtAbility->bind_param("i", $lineup["AbilityId"]);
    $stmtAbility->execute();
    $resultAbility = $stmtAbility->get_result();
    $ability = $resultAbility->fetch_assoc();

    $lineup["Ability"] = $ability;

    $sqlAgent = "SELECT * FROM Agents WHERE ID = ?";
    $stmtAgent = $conn->prepare($sqlAgent);
    $stmtAgent->bind_param("i", $ability["AgentId"]);
    $stmtAgent->execute();
    $resultAgent = $stmtAgent->get_result();
    $agent = $resultAgent->fetch_assoc();

    $lineup["Agent"] = $agent;

    echo json_encode(array("code" => 200, "data" => $lineup));
} else {
    throwCode("Lineup not found", 404, "Lineup not found");
}