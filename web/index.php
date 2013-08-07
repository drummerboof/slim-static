<?php
require __DIR__.'/../vendor/autoload.php';
$config = require __DIR__.'/../app/config/app.php';

use \SlimStatic\View\PageManager,
    \SlimStatic\View\LayoutView,
    \SlimStatic\Route\Generator;

$app = new \Slim\Slim(array_merge($config, array(
    'templates.path' => __DIR__.'/../app/views'
)));

$app->hook('slim.before', function () use ($app) {
    $generator = new Generator($app);
    $routes = $generator->routes($app->getMode() !== 'production');
    $view = new LayoutView(new PageManager($app->request(), $routes), 'main.php');
    $app->view($view);
    $generator->apply($routes);
});

$app->error(function (\Exception $exception) use ($app) {
    $app->response()->status(500);
    $app->view()->appendData(array('exception' => $exception));
    echo $app->view()->render('generic.php', LayoutView::$ERROR);
});

$app->notFound(function () use ($app) {
    $app->response()->status(404);
    echo $app->view()->render('404.php', LayoutView::$ERROR);
});

$app->run();