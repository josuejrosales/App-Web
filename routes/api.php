<?php

use Controllers\Router;
use Models\Auth;

Router::get("/api/amigos", function ($request) {
   echo json_encode($request);
});

Router::get("/api/getTypeUser", function ($request) {

   $type = Auth::getInstance()->getUserType();

   echo json_encode([
      "operation" => true,
      "data" => base64_encode($type),
   ]);
}, ["authorization"]);

Router::post("/api/contactos", function ($request) {
   echo json_encode($request);
});


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

Router::handleRoute($uri, $method);
