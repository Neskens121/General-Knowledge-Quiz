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
// Create seed data
/*$seedData = array(
    [
        'decade' => '1970s', 
        'artist' => 'Debby Boone',
        'song' => 'You Light Up My Life', 
        'weeksAtOne' => 10
    ],
    [
        'decade' => '1980s', 
        'artist' => 'Olivia Newton-John',
        'song' => 'Physical', 
        'weeksAtOne' => 10
    ],
    [
        'decade' => '1990s', 
        'artist' => 'Mariah Carey',
        'song' => 'One Sweet Day', 
        'weeksAtOne' => 16
    ],
);*/


$uri = "mongodb://heroku_7hskhz92:Nb!66336623@ds249545.mlab.com:49545/heroku_7hskhz92";
$client = new MongoDB\Client($uri);
var_dump($client);
/*
 * First we'll add a few songs. Nothing is required to create the songs
 * collection; it is created automatically when we insert.
 */
$songs = $client->db->songs;
var_dump($songs);
echo "xxxxxx";
// To insert a dict, use the insert method.
$songs->insertMany([
    [
        'username' => 'admin',
        'email' => 'admin@example.com',
        'name' => 'Admin User',
    ],
    [
        'username' => 'test',
        'email' => 'test@example.com',
        'name' => 'Test User',
    ],
]);
/*
 * Then we need to give Boyz II Men credit for their contribution to
 * the hit "One Sweet Day".
*/
$songs->updateOne(
    array('artist' => 'Mariah Carey'), 
    array('$set' => array('artist' => 'Mariah Carey ft. Boyz II Men'))
);
/*
 * Finally we run a query which returns all the hits that spent 10 
 * or more weeks at number 1. 
*/

$query = array('weeksAtOne' => array('$gte' => 10));
$options = array(
    "sort" => array('decade' => 1),
);
$cursor = $songs->find($query,$options);
foreach($cursor as $doc) {
    echo 'In the ' .$doc['decade'];
    echo ', ' .$doc['song']; 
    echo ' by ' .$doc['artist'];
    echo ' topped the charts for ' .$doc['weeksAtOne']; 
    echo ' straight weeks.', "\n";
}

return true;
});



$app->run();
