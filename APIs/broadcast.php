<?php
if((strlen($_POST['company']) < 1) || (strlen($_POST['shares']) < 1) || (strlen($_POST['share_price']) < 1) || (strlen($_POST['team_name']) < 1)) {
        $jsonDoc = array("status" => 400, "code" => 0, "message" => "Missing parameters");
        echo(json_encode($jsonDoc));
        exit;
}


$company = $_POST['company'];
$shares = intval($_POST['shares']);
$share_price = $_POST['share_price'];
$team_name = $_POST['team_name'];

if($shares < 1) {
        $jsonDoc = array("status" => 400, "code" => 0, "message" => "Invalid input");
        echo(json_encode($jsonDoc));
        exit;
}

$m = new MongoClient();
$db = $m->MockStock; 
$collection1 = $db->sellerBoard;

$collection2 = $db->gameData;
$resp_team = $collection2->findOne(array("team_name" => $team_name));

if($resp_team == NULL) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => "Team does not exist");
        echo(json_encode($jsonDoc));
        exit;
}

$temp = $resp_team['shares'];
foreach($temp as $t) {
	if($t['company'] == $company) {
		if($t['shares'] < $shares) {
			$jsonDoc = array("status" => 400, "code" => 0, "message" => "Not enough shares to sell");
        		echo(json_encode($jsonDoc));
		        exit;
		}
	}
}

$resp = $collection1->findOne(array("company" => $company, "team_name" => $team_name));
if($resp != NULL){
	$doc = array('$set' => array("share_price" => $share_price, "shares" => $shares));
	$collection1->update(array("team_name" => $team_name, "company" => $company), $doc, array());
	$jsonDoc = array("status" => 200, "code" => 1, "message" => "Seller board updated");
        echo(json_encode($jsonDoc));
        exit;
}

$collection1->insert(array("team_name" => $team_name, "company" => $company, "share_price" => $share_price, "shares" => $shares));
$jsonDoc = array("status" => 200, "code" => 1, "message" => "Sent to seller board");
echo(json_encode($jsonDoc));
?>
