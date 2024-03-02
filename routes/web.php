
<?php

use Controllers\Router;
use Models\Auth;

Router::get("/", function () {
    FrontController::renderView("layout");
}, ["Authentication"]);

Router::get("/login", function () {

    FrontController::renderView("login");
}, ["Authentication", "SessionReset"]);


Router::post("/login-in", fn ($rqst) => LoginIn($rqst));

Router::get("/close", function () {
    $auth = Auth::getInstance();
    $auth->logout();
    Router::redirect("/login");
});

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

Router::handleRoute($uri, $method);
