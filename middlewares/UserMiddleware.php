<?php

use Controllers\Router;
use Models\Auth;
use Models\Validate;

function Authentication($uri)
{
    $auth = Auth::getInstance();

    switch ($uri) {
        case "/":
            !$auth->isAuthenticated() && Router::redirect("/login");
            break;
        case "/login":
            $auth->isAuthenticated() && Router::redirect("/");
            break;
    }
}

function SessionReset()
{
    $bool = $_SESSION["MSG_REQUEST_STATE"] ?? "RESET";
    if ($bool == "RESET") {
        unset($_SESSION["MSG_REQUEST"]);
        unset($_SESSION["MSG_REQUEST_STATE"]);
    } else {
        $_SESSION["MSG_REQUEST_STATE"] = "RESET";
    }
}

function LoginIn($request)
{
    $validate = Validate::Rules([
        "usuario" => "required",
        "password" => "required",
    ])->validate($request);

    if (!$validate->correct()) {
        Router::redirect("/login", $validate->state->getData());
    }

    $type = $request["nivel_acceso"];
    $user = $request["usuario"];
    $password = $request["password"];

    $auth = Auth::getInstance();
    $result = $auth->login($user, $password, $type);

    if ($result->getOperation()) {
        $data = (object)$result->getData();
        setcookie("token", $data->token, time() + 3600, "/");
        Router::redirect("/");
    }

    Router::redirect("/login", $result->getData());
}
