<?php
#To check if team_name and the password match the entry in the DB

if((strlen($_GET['team_name']) < 1) || (strlen($_GET['password']) < 1)) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => "Missing parameters");
        echo(json_encode($jsonDoc));
        exit;
}

$teamName = $_GET['team_name'];
$pword = $_GET['password'];

$m = new MongoClient();
$db = $m->MockStock;
$collection = $db->playersData;
$response = $collection->findOne(array('team_name' => $teamName));
/*
$key = "mockstock";
$decrypted_pword = mcrypt_ecb(MCRYPT_DES, $key, $response['password'], MCRYPT_DECRYPT);
*/


if($response == NULL) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => "team_name does not exist");
        echo(json_encode($jsonDoc));
	exit;
}


if($response['password'] == $pword){
	$jsonDoc = array("status" => 200, "code" => 1, "message" => "team_name and password matching");
	echo(json_encode($jsonDoc));
	}
else {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => "team_name and password not matching");
        echo(json_encode($jsonDoc));
	}
?>
