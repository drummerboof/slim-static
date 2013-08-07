<?php

namespace SlimStatic\View;

use SlimStatic\Route\FileRoute;

class PageManager {

    /**
     * @var \Slim\Http\Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $pages = array();

    /**
     * @var array
     */
    protected $levels = array();

    /**
     * @param \Slim\Http\Request $request
     * @param array $routes
     */
    public function __construct(\Slim\Http\Request $request, array $routes)
    {
        $this->request = $request;
        $this->setRoutes($routes);
    }

    /**
     * Get a page by URL
     *
     * @param $url
     * @return Page|null
     */
    public function get($url)
    {
        return isset($this->pages[rtrim($url, '/')])
            ? $this->pages[rtrim($url, '/')]
            : new NotFoundPage($this->request);
    }

    /**
     * Get the current page based on the request path
     *
     * @return Page
     */
    public function current()
    {
        return $this->get(rtrim($this->request->getPathInfo(), '/'));
    }

    /**
     * Set the routes on which this page manager operates
     *
     * @param array $routes
     */
    public function setRoutes(array $routes)
    {
        foreach ($routes as $url => $route)
        {
            $page = new Page($this->request, $route);
            $this->pages[rtrim($url, '/')] = $page;
            $level = substr_count(trim($url, '/'), '/');
            if (!isset($this->levels[$level])) {
                $this->levels[$level] = array();
            }
            $this->levels[$level][] = $page;
        }
    }

    /**
     * Return an array of the immediate child pages of the page given.
     * If no page is given then the top level pages are returned.
     *
     * @param Page $parent
     * @return array
     */
    public function pages(Page $parent = null)
    {
        if ($parent === null) {
            return $this->levels[0];
        }
        if ($parent->level() === count($this->levels) - 1 || $parent->isHome()  ) {
            return array();
        }
        $pages = $this->levels[$parent ? $parent->level() + 1 : 0];
        $children = array_filter($pages, function ($page) use ($parent) {
            return strpos($page->url(), $parent->url()) === 0;
        });
        return array_values($children);
    }

}