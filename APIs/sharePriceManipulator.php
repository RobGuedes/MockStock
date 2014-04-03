<?php
$m = new MongoClient();
$db = $m->MockStock;
$collection = $db->ipo;
$cursor = $collection->find();

while(1) {
	foreach($cursor as $doc) {
		$resp = $db->companiesData->findOne(array('company' => $doc['company']));
		if($resp['shares'] < 0.7*)
	}
}
?>
