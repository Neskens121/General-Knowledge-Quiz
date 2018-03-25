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
	} else{
		$_SESSION = array();
		$uri = "mongodb://testUser:12345!@ds249545.mlab.com:49545/heroku_7hskhz92";
		$client = new MongoDB\Client($uri);
		$db = $client->heroku_7hskhz92;
		$cursor = $db->questions->find([],['projection' => ['question' => 1, 'potentialAnswers' => 1]]);
		$cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
		//var_dump($cursor->toArray());
		$queryResultArr = $cursor->toArray();
		/*$testArr = [];
		for($i = 0; $i < count($queryResultArr) ; $i++){
			$testArr[$i] = $queryResultArr[$i];
		}

		var_dump($testArr);
		*/
		foreach ($queryResultArr as $value) {
			if(isset($value['_id'])){
				echo ((array)$value['_id'])['oid'];
			} else {

			}
			//var_dump(((array)$value['_id'])['oid']);
		}

		$_SESSION['questionIndexArr'] = array_rand($queryResultArr, 5);
		var_dump($_SESSION);
		shuffle($_SESSION['questionIndexArr']);
		foreach ($_SESSION['questionIndexArr'] as $key => $value) {
			$tempQuestionArr[] = $queryResultArr[$value];
		}
		$_SESSION['userAnswerArr'] = array();
		var_dump($tempQuestionArr);

		return $app['twig']->render('db.twig', array('questions' => $questions));
		if(isset($_POST['startBtn'])){
			$app['monolog']->addDebug('logging output.');
			var_dump($_POST);
			return $app['twig']->render('question.twig');	
		} else {
			$app['monolog']->addDebug('logging output.');
			var_dump($_POST);
			return $app['twig']->render('quizResult.twig');
		}
	}
});






$app->run();
