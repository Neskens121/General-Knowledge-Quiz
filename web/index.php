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

$uri = "mongodb://testUser:12345!@ds117539.mlab.com:17539/heroku_wzb3tkp3";
//$uri = "mongodb://testUser:12345!@ds249545.mlab.com:49545/heroku_7hskhz92";
$client = new MongoDB\Client($uri);
//var_dump($client);

$inventory = $client->inventory;
var_dump($inventory);




$cursor = $inventory->find([]);

var_dump($cursor);

foreach($cursor as $doc) {
    echo "A<br>";
    echo 'In the ' .$doc['item'];
    echo ', ' .$doc['qty']; 
    echo ' by ' .$doc['status'];
}
echo "xxxxxx";
return true;
});



$app->run();
