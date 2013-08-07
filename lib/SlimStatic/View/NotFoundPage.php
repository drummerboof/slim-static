<?php

namespace SlimStatic\View;

class NotFoundPage extends Page {

    public function __construct(\Slim\Http\Request $request)
    {
        $this->request = $request;
    }

    /**
     * Return true if this page's route matches the current request path
     *
     * @return bool
     */
    public function isCurrent()
    {
        return true;
    }

    /**
     * Get the title for this page based on the route view filename
     *
     * @return string
     */
    public function title()
    {
        return 'Error 404';
    }

    /**
     * Get the page ID based on the url. For a page with the url /this/that the ID will be this-that
     *
     * @return mixed|string
     */
    public function id()
    {
        return 'error-404';
    }

    /**
     * Get the URL for this page
     *
     * @return string
     */
    public function url()
    {
        return $this->request->getPath();
    }

    /**
     * Return the level of the page in the hierarchy
     *
     * @return int
     */
    public function level()
    {
        return 0;
    }

    /**
     * Return true if this is the homepage
     *
     * @return bool
     */
    public function isHome()
    {
        return false;
    }
}