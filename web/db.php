<?php 
require('../vendor/autoload.php');


$uri = "mongodb://testUser:12345!@ds249545.mlab.com:49545/heroku_7hskhz92";
$client = new MongoDB\Client($uri);
$db = $client->heroku_7hskhz92;
$cursor = $db->questions->find([]);
$cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
$queryResultArr = $cursor->toArray();
for($i = 0; $i < count($queryResultArr); $i++){
	foreach ($queryResultArr[$i] as $key => $value) {
		if(is_object($value)){
			$testArr[$i][$key] = ((array)$value)['oid'];
		} else {
			$testArr[$i][$key] = $value;
		}
	}
}