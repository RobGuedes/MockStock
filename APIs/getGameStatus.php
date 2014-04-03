<?php
# Returns the status of the game of a particular team

if(strlen($_GET['team_name']) < 1) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => "Missing parameters");
        echo(json_encode($jsonDoc));
        exit;
}

$team_name = $_GET['team_name'];

$m = new MongoClient();
$db = $m->MockStock;
$collection = $db->gameData;
$resp = $collection->findOne(array("team_name" => $team_name));
if($resp == NULL) {
	$jsonDoc = array("status" => 200, "code" => 0, "message" => "No game status found for this team");
	echo(json_encode($jsonDoc));
	exit;
	}
else {
	$jsonDoc = array("status" => 200, "code" => 1, "message" => "Game status found for this team", "game_status" => $resp);
        echo(json_encode($jsonDoc));
	}
?>
