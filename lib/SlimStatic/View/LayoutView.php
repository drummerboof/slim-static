<?php

namespace SlimStatic\View;

/**
 * Class LayoutView
 * @package SlimStatic\View
 *
 * LayoutView provides a means to use layouts to wrap individual template output.
 */
class LayoutView extends \Slim\View
{

    public static $PARTIAL = 'partials/';
    public static $LAYOUT = 'layouts/';
    public static $ERROR = 'errors/';
    public static $PAGE = 'pages/';

    /**
     * @var string
     */
    protected $layout;

    /**
     * @var \SlimStatic\View\PageManager
     */
    protected $pages;

    /**
     * @param PageManager $pageManager
     * @param null $layout
     */
    public function __construct(PageManager $pageManager, $layout = null)
    {
        $this->pages = $pageManager;
        if ($layout) {
            $this->setLayout($layout);
        }
    }

    /**
     * Set the layout file. The contents of this file will be used to wrap the output returned
     * from calling render.
     *
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = self::$LAYOUT . $layout;
    }

    /**
     * Render a template file. If a layout has been given then the result of rendering this
     * template is set as the $_content variable within the layout template which is then
     * rendered and returned.
     *
     * @param string $template
     * @param null|string $prefix
     * @return string
     */
    public function render($template, $prefix = null)
    {
        if ($this->hasLayout())
        {
            $content = parent::render(($prefix ? $prefix : self::$PAGE) . $template);
            $this->appendData(array(
                '_content' => $content
            ));
            return parent::render($this->layout);
        }
        else
        {
            return parent::render($template);
        }
    }

    /**
     * Render a template bypassing any of the layout logic.
     * Used for rendering templates within a template
     *
     * @param string $template
     * @param array $data
     * @return string
     */
    public function partial($template, $data = array())
    {
        if (!empty($data)) {
            $this->appendData($data);
        }
        return parent::render(self::$PARTIAL . $template);
    }

    /**
     * Return true if a layout has been set on the view
     *
     * @return bool
     */
    public function hasLayout()
    {
        return $this->layout !== null;
    }

    /**
     * Get the page manager
     *
     * @return \SlimStatic\View\PageManager
     */
    public function pages()
    {
        return $this->pages;
    }

    /**
     * Get the current page
     *
     * @return Page
     */
    public function page()
    {
        return $this->pages->current();
    }

}