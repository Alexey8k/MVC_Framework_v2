<?php

namespace App\Core\View;

use App\Core\Session\Session;

class View
{
    public $viewBag = [];

    private $_layout;

    public function __construct()
    {
        Session::start();
    }

    public function renderView(string $pathContentView, $model = null)
    {
        $renderBody = $this->renderBody($pathContentView, $model);
        $viewBag = $this->viewBag;

        include $this->_layout;
    }

    public function renderPartialView(string $pathContentView, $model = null)
    {
        $viewBag = $this->viewBag;

        include $pathContentView;
    }

    private function renderBody(string $pathContentView, $model)
    {
        $layout = null;
        $viewBag = $this->viewBag;
        ob_start();
        include $pathContentView;
        $this->_layout = $layout;
        $this->viewBag = array_merge($this->viewBag, $viewBag);
        return ob_get_clean();
    }
}