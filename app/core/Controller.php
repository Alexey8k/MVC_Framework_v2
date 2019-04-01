<?php

namespace App\Core;

use App\Core\View\{View,ViewBag};
use App\Core\Route\Routing;

abstract class Controller {

    //protected $model;
    private $_view;
    protected $viewBag;

    public function __construct()
    {
        $this->_view = new View();
        $this->viewBag = new ViewBag($this->_view);
    }

    protected function getPathToFile(string $fileName)
    {
        return 'app/views/' . $this->getDirectory() . '/' . $fileName . '.php';
    }

    protected function view($model = null)
    {
        $this->_view->renderView($this->getPathToFile(Routing::getRouteData()['action']), $model);
    }

    protected function partialView($model = null)
    {
        $this->_view->renderPartialView($this->getPathToFile(Routing::getRouteData()['action']), $model);
    }

    protected function redirect(string $url)
    {
        header('Location: ' . $url);
    }

    protected function redirectToAction(string $action, string $controller, array $routeValues = null)
    {
        header('Location: ' . Routing::getUrl(array_merge(['action'=>$action,'controller'=>$controller], $routeValues ?? [])));
    }

    protected function imageFile(string $fileContents)
    {
        //$data = base64_decode($fileContents);
        $im = imagecreatefromstring($fileContents);
        imagealphablending($im, true);
        imagesavealpha($im, true);
        if ($im !== false) {
            $mime = getimagesizefromstring($fileContents)['mime'];
            header("Content-Type: $mime");
            switch ($mime) {
                case 'image/gif' :
                    imagegif($im);
                    break;
                case 'image/png' :
                    imagepng($im);
                    break;
                case 'image/jpeg' :
                    imagejpeg($im);
                    break;
                //case 'image/bmp' : imagebmp($im); break;
                //case 'image/webp' : imagewebp($im); break;
                default :
                    imagepng($im);
                    break;
            }
            imagedestroy($im);
        }
    }

    private function getDirectory()
    {
        preg_match("/\w+(?=Controller)/", get_called_class(), $array);
        return $array[0];
    }
}