<?php

namespace SlimStatic\View;

class LayoutView extends \Slim\View
{

    public static $PARTIAL = 'partials/';
    public static $LAYOUT = 'layouts/';
    public static $ERROR = 'errors/';
    public static $PAGE = 'pages/';

    protected $layout;

    public function __construct($layout = null)
    {
        $this->setLayout($layout);
    }

    public function setLayout($layout)
    {
        $this->layout = self::$LAYOUT . $layout;
    }

    public function render($template, $prefix = null)
    {
        if ($this->hasLayout())
        {
            $content = parent::render(($prefix ? $prefix : self::$PAGE) . $template);
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
        return parent::render(self::$PARTIAL . $template);
    }

    public function hasLayout()
    {
        return $this->layout !== null;
    }

}