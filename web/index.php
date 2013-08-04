<?php
require __DIR__.'/../vendor/autoload.php';

$app = new \Slim\Slim(array(
    'view' => new \SlimStatic\View\LayoutView('main.php'),
    'templates.path' => __DIR__.'/../app/views'
));

$app->get('/', function () use ($app) {
    $app->render('index.php', array(
        'title' => 'Home'
    ));
});

$app->run();