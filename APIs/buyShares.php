<?php
if((strlen($_POST['company']) < 1) || (strlen($_POST['shares']) < 1) || (strlen($_POST['from']) < 1) || (strlen($_POST['team_name']) < 1)) {
        $jsonDoc = array("status" => 400, "code" => 0, "message" => "Missing parameters");
        echo(json_encode($jsonDoc));
        exit;
}

$company = $_POST['company'];
$shares = intval($_POST['shares']);
$from = $_POST['from'];
$team_name = $_POST['team_name'];


$m = new MongoClient();
$db = $m->MockStock; 

$collection1 = $db->sellerBoard;
$collection2 = $db->gameData;

$sellerResp = $collection1->findOne(array("team_name" => $from, "company" => $company));
if($sellerResp == NULL) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => $from." is not selling any shares of ".$company);
        echo(json_encode($jsonDoc));
        exit;
}

if($sellerResp['shares'] < $shares) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => $from." does not have ".$shares." shares of ".$company);
        echo(json_encode($jsonDoc));
        exit;
}

$teamResp = $collection2->findOne(array("team_name" => $team_name));
$cashReq = $sellerResp['share_price']*$shares;
$fromResp = $collection2->findOne(array("team_name" => $from));

if($shares == $sellerResp['shares']) {
	if($teamResp['cash'] > $cashReq) {
		$collection1->remove(array("team_name" => $from, "company" => $company), array("justOne" => true));
		$shrs = $fromResp['shares'];
		$newShrs = array();
		foreach($shrs as &$s) {
			if($s["company"] != $company){
				array_push($newShrs, $s);
			}
			if($s["company"] == $company){
				if($s["shares"]-$shares != 0){
					$s["shares"] = $s["shares"]-$shares;
					array_push($newShrs, $s);
				}
			}
		}
		$doc = array('$set' => array("shares" => $newShrs));
		$collection2->update(array("team_name" => $from), $doc, array());
		$doc = array('$set' => array("cash" => $fromResp['cash']+$cashReq));
		$collection2->update(array("team_name" => $from), $doc, array());
		$shrs = $teamResp["shares"];
		$flag = 0;
		foreach($shrs as &$s) {
			if($s["company"] == $company) {
				$s["shares"] = $s["shares"]+$shares;
				$flag = 1;
			}
		}
		if($flag == 0) {
			array_push($shrs, array("company" => $company, "shares" => $shares));
		}
		$doc = array('$set' => array("shares" => $shrs));
		$collection2->update(array("team_name" => $team_name), $doc, array());
		$doc = array('$set' => array("cash" => $teamResp['cash']-$cashReq));
		$collection2->update(array("team_name" => $team_name), $doc, array());
		$jsonDoc = array("status" => 200, "code" => 1, "message" => "Transaction successful");
        	echo(json_encode($jsonDoc));
		exit;		
	}
	else {
		echo $teamResp["cash"];
		echo $cashReq;
		$jsonDoc = array("status" => 400, "code" => 0, "message" => "I landed here");
                echo(json_encode($jsonDoc));
		exit;
	}
}
else {
	if($teamResp['cash'] > $cashReq) {
		$doc = array('$set' => array("shares" => $sellerResp['shares']-$shares));
		$collection1->update(array("team_name" => $from, "company" => $company), $doc, array());
		$shrs = $fromResp['shares'];
		$newShrs = array();
		foreach($shrs as &$s) {
			if($s["company"] != $company){
				array_push($newShrs, $s);
			}
			if($s["company"] == $company){
				if($s["shares"]-$shares != 0){
					$s["shares"] = $s["shares"]-$shares;
					array_push($newShrs, $s);
				}
			}
		}
		$doc = array('$set' => array("shares" => $newShrs));
		$collection2->update(array("team_name" => $from), $doc, array());
		$doc = array('$set' => array("cash" => $fromResp['cash']+$cashReq));
		$collection2->update(array("team_name" => $from), $doc, array());
		$shrs = $teamResp["shares"];
		$flag = 0;
		foreach($shrs as &$s) {
			if($s["company"] == $company) {
				$s["shares"] = $s["shares"]+$shares;
				$flag = 1;
			}
		}
		if($flag == 0) {
			array_push($shrs, array("company" => $company, "shares" => $shares));
		}
		$doc = array('$set' => array("shares" => $shrs));
		$collection2->update(array("team_name" => $team_name), $doc, array());
		$doc = array('$set' => array("cash" => $teamResp['cash']-$cashReq));
		$collection2->update(array("team_name" => $team_name), $doc, array());
		$jsonDoc = array("status" => 200, "code" => 1, "message" => "Transaction successful");
        	echo(json_encode($jsonDoc));
		exit;		
	}
	else {
		echo $teamResp["cash"];
		echo $cashReq;
		$jsonDoc = array("status" => 400, "code" => 0, "message" => "I landed here");
                echo(json_encode($jsonDoc));
		exit;
	}
}







?>
