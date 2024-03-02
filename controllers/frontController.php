<?php


use Models\Model;

class FrontController
{
    public function __construct()
    {
        Model::createConnection();
    }
    /**
     * @param string $uri Ruta de inicio del programa 
     */
    public function handleRoute($uri)
    {
        $route_api = explode('/', trim($uri, '/'))[0];

        switch (strtolower($route_api)) {
            case 'api':
                require_once(__DIR__ . "./../routes/api.php");
                break;
            default:
                require_once(__DIR__ . "./../routes/web.php");
        }
    }

    public static function renderView($view)
    {
        include __DIR__ . "./../public/$view.php";
    }
}


require_once(__DIR__ . "./../controllers/routeController.php");
require_once(__DIR__ . "./../middlewares/UserMiddleware.php");
require_once(__DIR__ . "./../middlewares/ApiMiddleware.php");
