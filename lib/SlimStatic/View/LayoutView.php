<?php

namespace SlimStatic\View;

class LayoutView extends \Slim\View
{

    protected static $PARTIALS_DIR = 'partials/';
    protected static $LAYOUTS_DIR = 'layouts/';
    protected static $PAGES_DIR = 'pages/';

    protected $layout;

    public function __construct($layout = null)
    {
        $this->setLayout($layout);
    }

    public function setLayout($layout)
    {
        $this->layout = self::$LAYOUTS_DIR . $layout;
    }

    public function render($template)
    {
        if ($this->hasLayout())
        {
            $content = parent::render(self::$PAGES_DIR . $template);
            $this->setData(array_merge($this->getData(), array(
                '_content' => $content
            )));
            return parent::render($this->layout);
        }
        else
        {
            return parent::render($template);
        }
    }

    public function partial($template, $data = array())
    {
        if (!empty($data)) {
            $this->setData($data);
        }
        return parent::render(self::$PARTIALS_DIR . $template);
    }

    public function hasLayout()
    {
        return $this->layout !== null;
    }

}