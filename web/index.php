<?php
require __DIR__.'/../vendor/autoload.php';
$config = require __DIR__.'/../app/config/app.php';

$app = new \Slim\Slim(array_merge($config, array(
    'view' => new \SlimStatic\View\LayoutView('main.php'),
    'templates.path' => __DIR__.'/../app/views'
)));

$app->hook('slim.before', function () use ($app) {
    $generator = new \SlimStatic\Route\Generator($app);
    $generator->apply($generator->generate(
        $app->getMode() !== 'production'
    ));
});

$app->error(function (\Exception $exception) use ($app) {
    $app->view()->appendData(array(
        'exception' => $exception,
        'title' => 'Error'
    ));
    echo $app->view()->render('generic.php', \SlimStatic\View\LayoutView::$ERROR);
});

$app->notFound(function () use ($app) {
    $app->response()->status(404);
    $app->view()->appendData(array(
        'title' => 'Page Not Found'
    ));
    echo $app->view()->render('404.php', \SlimStatic\View\LayoutView::$ERROR);
});

$app->run();