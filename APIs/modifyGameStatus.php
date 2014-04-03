<?php

if((strlen($_GET['company']) < 1) || (strlen($_GET['shares']) < 1) || (strlen($_GET['action']) < 1) || (strlen($_GET['team_name']) < 1)) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => "Missing parameters");
        echo(json_encode($jsonDoc));
        exit;
}


$company = $_GET['company'];
$shares = intval($_GET['shares']);
$action = $_GET['action'];
$team_name = $_GET['team_name'];

if($shares < 1) {
	$jsonDoc = array("status" => 400, "code" => 0, "message" => "Invalid input");
        echo(json_encode($jsonDoc));
        exit;
}

$m = new MongoClient();
$db = $m->MockStock;
$collection1 = $db->companiesData;
$resp_comp = $collection1->findOne(array("company" => $company));

$collection2 = $db->gameData;
$resp_team = $collection2->findOne(array("team_name" => $team_name));

if(($action == "buy") && ($resp_comp['shares'] >= $shares)) {
	$price = intval($resp_comp['share_price']);
	$cash = $shares * $price;
	$balance = intval($resp_team['cash']);
	if($balance > $cash) {
		$arr = $resp_team['shares'];
		$flag = 0;
		foreach ($arr as &$a) {
			#echo $a['shares'];
			if($a['company'] == $company) {
				$a['shares'] = $a['shares']+$shares;	
				#echo $a['shares'];
				$flag = 1;	
			}
		}
		if($flag == 0) {
			$temp = array("company" => $company, "shares" => $shares);
			array_push($arr, $temp);
		}
		$doc = array('$set' => array("shares" => $arr, "cash" => $resp_team['cash']-$cash));
		$collection2->update(array("team_name" => $team_name), $doc, array());
		$doc = array('$set' => array("shares" => $resp_comp['shares']-$shares));
		$collection1->update(array("company" => $company), $doc, array());	
	}
	if($balance < $cash) {
		$jsonDoc = array("status" => 200, "code" => 0, "message" => "Not enough cash with the team");
	        echo(json_encode($jsonDoc));
        	exit;
	}
	$resp_team = $collection2->findOne(array("team_name" => $team_name));
	$jsonDoc = array("status" => 200, "code" => 1, "mongo_response" => $resp_team);
	echo(json_encode($jsonDoc));
	exit;
}
if(($action == "buy") && ($resp_comp['shares'] < $shares)) {
	$jsonDoc = array("status" => 200, "code" => 0, "message" => "Required number of shares not available");
	echo(json_encode($jsonDoc));
        exit;
}

$arr = $resp_team['shares'];
$flag = 0;
foreach($arr as $a) {
	if($a['company'] == $company) {
		$shrs = $a['shares'];
		$flag = 1;
	}
}

if($flag == 0) {
	$jsonDoc = array("status" => 200, "code" => 0, "message" => "This team does not own any shares of this company to sell");
        echo(json_encode($jsonDoc));
        exit;
}
if(($action == "sell") && ($shrs >= $shares)) {
	$price = intval($resp_comp['share_price']);
        $cash = $shares * $price;
	$arr = $resp_team['shares'];
                $flag = 0;
                foreach ($arr as &$a) {
                        #echo $a['shares'];
                        if($a['company'] == $company) {
                                $a['shares'] = $a['shares']-$shares;
                                #echo $a['shares'];
                                $flag = 1;
                        }
                }
                if($flag == 0) {
                        $jsonDoc = array("status" => 200, "code" => 0, "message" => "This team does not own any shares of this company to sell");
        		echo(json_encode($jsonDoc));
        		exit;
                }
	$doc = array('$set' => array("shares" => $arr, "cash" => $resp_team['cash']+$cash));
        $collection2->update(array("team_name" => $team_name), $doc, array());
	$doc = array('$set' => array("shares" => $resp_comp['shares']+$shares));
        $collection1->update(array("company" => $company), $doc, array());
	$resp_team = $collection2->findOne(array("team_name" => $team_name));
        $jsonDoc = array("status" => 200, "code" => 1, "mongo_response" => $resp_team);
        echo(json_encode($jsonDoc));
        exit;
}

if(($action == "sell") && ($shrs < $shares)) {
	$jsonDoc = array("status" => 200, "code" => 0, "message" => "This team does not have the required number of shares to sell");
        echo(json_encode($jsonDoc));
        exit;
}
?>
