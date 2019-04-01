<?php

namespace App\Core\Html;

use App\Core\Route\Routing;
use App\Core\Url\Url;
use App\Core\File;
use App\Core\Html\Model\PagingInfo;

abstract class Html
{
    public static function renderAction(string $action, string $controller)
    {
        Routing::actionExecute($action, $controller);
    }

    public static function routeLink(string $linkText, array $routeValues = null, array $htmlAttributes = null)
    {
        $href = Routing::getUrl($routeValues ?? []);
        $a = new HtmlElement('a', array_merge($htmlAttributes ?? [], ['text' => $linkText, 'href' => $href]));
        return $a->toString();
    }

    public static function actionLink(string $linkText, string $action, string $controller = null, array $queryParams = null, array $htmlAttributes = null)
    {
        $href = Url::action($action, $controller ?? Routing::getRouteData()['controller'], $queryParams);
        $a = new HtmlElement('a', array_merge($htmlAttributes ?? [], ['text' => $linkText, 'href' => $href]));
        return $a->toString();
    }

    public static function renderPartial(string $partialViewName, $model = null)
    {
        include File::findFile("$partialViewName.php", "app/views");
    }

    public static function pageLinks(PagingInfo $pagingInfo, callable $pageUrl) : string
    {
        $result = "";

        for ($i = 1; $i <= $pagingInfo->totalPages; $i++)
        {
            $a = new HtmlElement('a', ['text'=>$i,'href'=>$pageUrl($i)]);
            if ($i == $pagingInfo->currentPage)
                $a->setAttr('class', 'selected');
            $result .= $a->toString();
        }

        return $result;
    }
}