<?php
require __DIR__.'/../vendor/autoload.php';

$app = new \Slim\Slim(array(
    'view' => new \SlimStatic\View\LayoutView('main.php'),
    'templates.path' => __DIR__.'/../app/views'
));

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

$app->get('/', function () use ($app) {
    $app->render('index.php', array(
        'title' => 'Home'
    ));
});

$app->run();