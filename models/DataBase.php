<?php

namespace Models;

use PDO;
use PDOException;

class DataBase
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=empresa_db";
        $username = "root";
        $password = "";

        try {
            $this->connection = new PDO($dsn, $username, $password);
            // Establece el modo de manejo de errores PDO para lanzar excepciones.
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Conexión fallida: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

require_once("./models/Model.php");
require_once("./models/Validate.php");
require_once("./models/StateOperation.php");
require_once("./models/QueryBuilder.php");

require_once("./models/database/Usuarios.php");
require_once("./models/database/Empleados.php");
require_once("./models/database/Auth.php");
