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
  echo "s";


$uri = "mongodb://testUser:12345!@ds249545.mlab.com:49545/heroku_7hskhz92";
$client = new MongoDB\Client($uri);
var_dump($client);

$songs = $client->db->songs;
var_dump($songs);

echo "xxxxxx";


/*$cursor = $songs->find({});

foreach($cursor as $doc) {
    echo 'In the ' .$doc['decade'];
    echo ', ' .$doc['song']; 
    echo ' by ' .$doc['artist'];
    echo ' topped the charts for ' .$doc['weeksAtOne']; 
    echo ' straight weeks.', "\n";
}*/

return true;
});



$app->run();
