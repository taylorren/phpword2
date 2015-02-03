<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once 'vendor/autoload.php';
require_once 'classWord.php';

$app=new Silex\Application();
$app['debug']=true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path'=>'views',
));

$app->get('/', function () use ($app)
{
    $c=new trWord\Word();
    return $c->index($app);
});

$app->post('export', function (Symfony\Component\HttpFoundation\Request $req) use ($app)
{
    $c=new trWord\Word();
    return $c->export($app, $req);
});
$app->run();