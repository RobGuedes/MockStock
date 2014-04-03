<?php
# To add a new team to the DB

if((strlen($_POST['team_name']) < 1) || (strlen($_POST['phone']) < 1) || (strlen($_POST['p1']) < 1) || (strlen($_POST['password']) < 1) || (strlen($_POST['p2']) < 1)) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => "Parameters missing");
        echo(json_encode($jsonDoc));
        exit;
}

$teamName = $_POST['team_name'];
$phone = $_POST['phone'];
$p1 = $_POST['p1'];
$p2 = $_POST['p2'];
$password = $_POST['password'];

/*
$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
$key_size =  strlen($key);
$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $password, MCRYPT_MODE_CBC, $iv);
$ciphertext = $iv . $ciphertext;
*/

$m = new MongoClient();
$db = $m->MockStock;
$collection = $db->playersData;
$response = $collection->findOne(array("team_name" => $teamName));

if($response!=NULL) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => "Team-name already exists");
	echo(json_encode($jsonDoc));
	exit;
	}
else {
	$document = array("team_name" => $teamName, "players" => array($p1, $p2), "phone" => $phone, "password" => $password);
	$collection->insert($document);
	$collection = $db->gameData;
	$document = array("team_name" => $teamName, "shares" => array(), "cash" => 10000);
	$collection->insert($document);
	$jsonDoc = array("status" => 200, "code" => 1, "message" => "Team-details added");
        echo(json_encode($jsonDoc));
	}
?>
