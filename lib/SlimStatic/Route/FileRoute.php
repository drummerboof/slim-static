<?php

namespace SlimStatic\Route;

/**
 * Class FileRoute
 * @package SlimStatic\Route
 */
class FileRoute
{
    /**
     * @var \SplFileInfo
     */
    protected $file;

    /**
     * @var string
     */
    protected $directory;

    public function __construct(\SplFileInfo $file, $directory = '')
    {
        $this->directory = realpath($directory);
        $this->file = $file;
    }

    /**
     * Get the route path for this view file
     *
     * @return string
     */
    public function route()
    {
        $base = $this->file->getBasename('.php');
        $path = str_replace($this->directory, '', $this->file->getPath());
        $route = $path . ($base === 'index' ? '' : '/' . $base);
        return $route ? $route . '/' : '/';
    }

    /**
     * Get the relative path to the view file for this route
     *
     * @return string
     */
    public function view()
    {
        return trim(str_replace(realpath($this->directory), '', $this->file->getRealPath()), '/');
    }

    /**
     * Get the hook name for this route
     *
     * @return string
     */
    public function hook()
    {
        return 'routes.' . ($this->route() === '/' ? 'index' : str_replace('/', '.', trim($this->route(), '/')));
    }
}