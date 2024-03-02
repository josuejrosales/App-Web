<?php

require_once("./controllers/frontController.php");
require_once("./models/DataBase.php");

$uri = $_SERVER['REQUEST_URI'];

$frontController = new FrontController();
$frontController->handleRoute($uri);
