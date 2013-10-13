<?php

namespace SlimStatic\Route;

use Slim\Slim;

/**
 * RouteGenerator scans the view directory and creates routes for all of
 * the pages contained within. A cached array representing the routes is
 * stored in the pages.lock file within the project root. In order to re-scan
 * the directory to pick up new pages, this file must be deleted.
 *
 * Class RouteGenerator
 * @package SlimStatic
 */
class Generator
{
    /**
     * @var \Slim\Slim
     */
    protected $app;

    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @param \Slim\Slim $app
     * @param array $config
     */
    public function __construct(Slim $app, array $config = array())
    {
        $this->app = $app;
        $root = rtrim($this->app->root(), 'index.php/');
        $this->config = array_merge(array(
            'view.path' => $root . '/../app/views/pages/',
            'lock.path' => $root . '/../app/cache/'
        ), $config);
        $this->fixPath();
    }

    /**
     * Generate the route array. If force is set to true the the view directory will always be scanned.
     * Otherwise the lock file will be used if present.
     *
     * @param bool $force
     * @return array
     */
    public function routes($force = false)
    {
        if (!empty($this->routes) && !$force) {
            return $this->routes;
        }

        if (!$this->lockExists() || $force) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($this->config['view.path'], \FilesystemIterator::SKIP_DOTS)
            );
            $files = array_map('realpath', array_keys(iterator_to_array($iterator)));
            $this->writeLock($files);
        } else {
            $files = $this->readLock();
        }

        foreach ($files as $path) {
            $file = new \SplFileInfo($path);
            if ($file->isFile() && $file->isReadable()) {
                $route = new FileRoute($file, $this->config['view.path']);
                if (!array_key_exists($route->route(), $this->routes)) {
                    $this->routes[$route->route()] = $route;
                }
            }
        }

        return $this->routes;
    }

    /**
     * Apply the given routes to the Slim application. Each route is applied to the app for
     * both GET and POST methods. A hook is added for each route in the form of routes.path.page
     * This hook can be used to add any additional custom functionality to a page.
     *
     * The title of the page is also set based on the $route->title() method.
     * This can be overridden in the view if required.
     *
     * @param array $routes
     */
    public function apply(array $routes)
    {
        /* @var FileRoute $route */
        foreach ($routes as $path => $route) {
            $this->app->map($path, function () use ($path, $route) {
                $this->app->applyHook($route->hook(), $this->app);
                $this->app->render($route->view());
            })->via('GET', 'POST');
        }
    }

    /**
     * @param $routes
     * @return int
     */
    protected function writeLock($routes)
    {
        return file_put_contents($this->lockFile(), json_encode($routes));
    }

    /**
     * Read and decode the contents of the lock file
     *
     * @return array
     */
    protected function readLock()
    {
        return json_decode(file_get_contents($this->lockFile()), true);
    }

    /**
     * Return true if the lock file exists
     *
     * @return bool
     */
    protected function lockExists()
    {
        return file_exists($this->lockFile());
    }

    /**
     * Get the path to the lock file
     *
     * @return string
     */
    protected function lockFile()
    {
        return realpath($this->config['lock.path']) . '/pages';
    }

    /**
     * Fix for an issue if running the application from the app root rather than web directory.
     * This removes the SCRIPT_NAME prefix if and only if it's part of the request URI path.
     *
     * @see http://help.slimframework.com/discussions/problems/769-url-routing-breaks-if-rewrite-rules-dont-match-underlying-structure
     */
    protected function fixPath()
    {
        $env = $this->app->environment();
        $requestUri = $_SERVER['REQUEST_URI'];
        $scriptName = $env['SCRIPT_NAME'];
        $prefixLen = strlen($scriptName);
        $requestUriNoParams = strpos($requestUri, '?') !== false ? substr($requestUri, 0, strpos($requestUri, '?')) : $requestUri;
        if (substr($requestUriNoParams, 0, $prefixLen) === $scriptName) {
            $env['PATH_INFO'] = substr_replace($requestUriNoParams, '', 0, $prefixLen);
        } else {
            $env['PATH_INFO'] = $requestUriNoParams;
        }
    }
}