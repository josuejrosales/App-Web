<?php

namespace Controllers;

class Router
{
    private static $routes = [];

    public static function get($ruta, $fun, $middlewares = [])
    {
        self::addRoute($ruta, $fun, $middlewares, 'GET');
    }

    public static function post($ruta, $fun, $middlewares = [])
    {
        self::addRoute($ruta, $fun, $middlewares, 'POST');
    }

    private static function addRoute($ruta, $fun, $middlewares, $method)
    {
        self::$routes[$ruta] = [
            'handler' => $fun,
            'middlewares' => $middlewares,
            'method' => $method,
        ];
    }

    public static function handleRoute($uri, $method)
    {
        $route = self::$routes[$uri] ?? null;

        if ($route !== null && is_object($route["handler"]) && $route['method'] === $method) {

            foreach ($route['middlewares'] as $middleware) {
                $middleware($uri);
            }

            // Obtener el cuerpo de la solicitud
            $body = file_get_contents("php://input");
            $data = $method === "POST" ? (json_decode($body, true) ?? $_POST) : $_REQUEST;
            $route['handler']($data);
        } else {
            self::responseMethodNotAllowed();
        }
    }

    public static function redirect($location, $params = [])
    {

        session_start();
        $before = [];
        foreach ($params as $key => $value) {
            $before[$key] = $value;
        }

        $_SESSION['MSG_REQUEST'] = $before;
        $_SESSION['MSG_REQUEST_STATE'] = "INITIAL";

        header("Location: $location");
        exit();
    }
    private static function responseMethodNotAllowed()
    {
        http_response_code(405);
        echo json_encode(['error' => 'Metodo no permitido para esta ruta']);
    }
}
