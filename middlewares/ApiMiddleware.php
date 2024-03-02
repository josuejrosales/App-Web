<?php

function authorization()
{
    try {
        $token_cookie = $_COOKIE["token"];
        if (!isset($token_cookie)) throw new Exception("token not localized.");

        $authorization = $_SERVER["HTTP_AUTHORIZATION"] ?: "";
        $token =  str_replace("Bearer ", "", $authorization);

        if ((bool)strcmp($token_cookie, $token)) {
            throw new Exception("Token is invalid.");
        }
    } catch (Exception $e) {
        echo json_encode([
            "operation" => false,
            "message" => $e->getMessage(),
        ]);
        exit();
    }
}
