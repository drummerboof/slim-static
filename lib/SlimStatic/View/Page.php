<?php

namespace SlimStatic\View;

class Page {

    /**
     * @var \Slim\Http\Request
     */
    protected $request;

    /**
     * @var \SlimStatic\Route\FileRpute
     */
    protected $route;


    public function __construct(\Slim\Http\Request $request, \SlimStatic\Route\FileRoute $route)
    {
        $this->request = $request;
        $this->route = $route;
    }

    /**
     * Return true if this page's route matches the current request path
     *
     * @return bool
     */
    public function isCurrent()
    {
        return rtrim($this->request->getPathInfo(), '/') === rtrim($this->route->route(), '/');
    }

    /**
     * Get the title for this page based on the route view filename
     *
     * @return string
     */
    public function title()
    {
        return $this->url() === '/' ? 'Home' : ucfirst(str_replace('-', ' ', basename(rtrim($this->route->route(), '/'))));
    }

    /**
     * Get the page ID based on the url. For a page with the url /this/that the ID will be this-that
     *
     * @return mixed|string
     */
    public function id()
    {
        return $this->url() === '/' ? 'home' : str_replace('/', '-', (trim($this->route->route(), '/')));
    }

    /**
     * Get the URL for this page
     *
     * @return string
     */
    public function url()
    {
        return $this->route->route();
    }

    /**
     * Return the level of the page in the hierarchy
     *
     * @return int
     */
    public function level()
    {
        return substr_count(trim($this->url(), '/'), '/');
    }

    /**
     * Return true if this is the homepage
     *
     * @return bool
     */
    public function isHome()
    {
        return $this->url() === '/';
    }

    /**
     * Whether or not this page exists.
     * Can be overridden in child classes (page not found)
     *
     * @return bool
     */
    public function exists()
    {
        return true;
    }
}