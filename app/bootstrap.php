<?php
require_once "core/BaseModel.php";
require_once 'core/view/View.php';
require_once 'core/view/ViewBag.php';
require_once 'core/Controller.php';
require_once 'core/Html/Html.php';
require_once 'core/Html/HtmlElement.php';
require_once 'core/session/Session.php';
require_once 'core/Interceptor/HandlerInterceptor.php';
require_once 'core/Interceptor/InterceptorManager.php';
require_once 'Exception/RouteException.php';
require_once 'Exception/ActionMethodException.php';
require_once 'Exception/ClassNotDefinedException.php';
require_once 'Exception/ControllerFileException.php';

require_once 'core/modelBinding/IModelBinder.php';
require_once 'core/modelBinding/ModelBinderDictionary.php';
require_once 'core/modelBinding/ModelBinders.php';
require_once 'core/modelBinding/DefaultModelBinder.php';

require_once 'core/Repository/Repository.php';
require_once 'core/Repository/Parameter.php';
require_once 'core/Repository/StoredProcedure.php';

require_once 'Repository/IBookRepository.php';
require_once 'Repository/BookCatalog.php';

require_once 'core/route/RouteCollection.php';
require_once 'core/route/Route.php';
require_once 'core/route/Routing.php';
require_once 'App_Start/RouteConfig.php';
require_once 'core/route/RoutePattern.php';

require_once 'core/File.php';

require_once 'core/url/Url.php';

require_once 'core/action/Action.php';

require_once 'App.php';

//try {
    App\App::start();
    App\Core\Route\Routing::start(); // запускаем маршрутизатор
//}
//catch (Exception $ex){
//    include "views/Shared/Error.php";
//}