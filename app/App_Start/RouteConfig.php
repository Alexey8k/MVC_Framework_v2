<?php

namespace App\App_Start;

use App\Core\Route\{RouteCollection};

class RouteConfig
{
    public static function registerRoutes(RouteCollection $routes)
    {

        $routes->mapRoute('',
            [
                'controller' => 'Public',
                'action' => 'Index',
            ]);

        $routes->mapRoute( 'books',
            [
                'controller' => 'Public',
                'action' => 'GetBooks',
                'page' => 1
            ]);

        $routes->mapRoute( 'books/page{page}',
            [
                'controller' => 'Public',
                'action' => 'GetBooks',
                'genreId' => null,
                'authorId' => null,
            ],
            ['page' => "\d+" ]);

        $routes->mapRoute('books/filter',
            [
                'controller' => 'Public',
                'action' => 'GetBooks',
                'page' => 1
            ]);

        $routes->mapRoute('books/filter/page{page}',
            [
                'controller' => 'Public',
                'action' => 'GetBooks',
            ],
            ['page' => "\d+" ]);

        $routes->mapRoute('book{id}/details',
            [
                'controller' => 'Public',
                'action' => 'GetBookDetails',
            ],
            ['id' => "\d+" ]);

        $routes->mapRoute('order',
            [
                'controller' => 'Public',
                'action' => 'Order',
            ]);

        $routes->mapRoute('admintool',
            [
                'controller' => 'AdminTool',
                'action' => 'Index'
            ]);

        $routes->mapRoute('admintool/delete/book{id}',
            [
                'controller' => 'AdminTool',
                'action' => 'DeleteBook'
            ],
            ['id' => "\d+" ]);

        $routes->mapRoute('dictionary/{name}',
            [
                'controller' => 'Public',
                'action' => 'GetDictionary'
            ]);

        $routes->mapRoute('{controller}/{action}');
    }
}