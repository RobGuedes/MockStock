<?php
# Add details of a company to the DB

if(strlen($_GET['company']) < 1 || strlen($_GET['capital']) < 1 || strlen($_GET['shares']) < 1 || strlen($_GET['share_price']) < 1) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => "Missing parameters");
	echo(json_encode($jsonDoc));
        exit;
}
$company = $_GET['company'];
$capital = intval($_GET['capital']);
$shares = intval($_GET['shares']);
$shareValue = intval($_GET['share_price']);

if(($capital < 1) || ($shares < 1) || ($shareValue < 1)) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => "Invalid Inputs");
        echo(json_encode($jsonDoc));
        exit;
}

$m = new MongoClient();
$db = $m->MockStock;
$collection = $db->companiesData;
$resp = $collection->findOne(array("company" => $company));
if($resp == NULL) {
	$doc = array("company" => $company, "capital" => $capital, "shares" => $shares, "share_price" => $shareValue);
	$collection->insert($doc);
	$jsonDoc = array("status" => 200, "code" => 1, "message" => "Company details added");
	echo(json_encode($jsonDoc));
	exit;
}
else {
	$doc = array('$set' => array("capital" => $capital, "shares" => $shares, "share_price" => $shareValue));
	$collection->update(array("company" => $company), $doc, array());
	$jsonDoc = array("status" => 200, "code" => 1, "message" => "Company details updated");
	echo(json_encode($jsonDoc));
	exit;
}
$jsonDoc = array("status" => 200, "code" => 0, "message" => "Company details not updated");
echo(json_encode($jsonDoc));
?>
