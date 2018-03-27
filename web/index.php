<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
	'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
	$app['monolog']->addDebug('logging output.');
	return $app['twig']->render('index.twig');
});


$app->get('/db', function() use($app) {
	$uri = "mongodb://testUser:12345!@ds249545.mlab.com:49545/heroku_7hskhz92";
	$client = new MongoDB\Client($uri);

  //var_dump($client);

	$db = $client->heroku_7hskhz92;
 //var_dump($db);

	$questions = $db->questions->find([]);
	return $app['twig']->render('db.twig', array('questions' => $questions));
});


$app->post('/', function() use($app) {
	session_start();
	var_dump($_SESSION);
	if(isset($_POST['logout'])){
		$_SESSION = array();
		if(isset($_COOKIE[session_name()])){
			setcookie(session_name(), '', time() -86400, '/');
		}
		session_destroy();
		return $app['twig']->render('index.twig');
	} elseif(isset($_POST['startBtn'])){
		//$app['monolog']->addDebug('logging output.');
		//var_dump($_POST);
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
		return $app['twig']->render('question.twig', array('questions' => $testArr, 'questionNumber' => $questionNumber));
		
	} elseif(isset($_POST['questionNumber'])) {
		var_dump($_POST);
		return $app['twig']->render('quizResult.twig');

		
		$_SESSION['questionIndexArr'] = array_rand($queryResultArr, 5);
		shuffle($_SESSION['questionIndexArr']);
		
		

		$questionNumber = $_POST['questionNumber'];

		
		foreach ($_SESSION['questionIndexArr'] as $key => $value) {
			$tempQuestionArr[] = $queryResultArr[$value];
		}
		$_SESSION['userAnswerArr'] = array();
	}
});

$app->run();
