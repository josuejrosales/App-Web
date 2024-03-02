<?php

namespace Models;

use Models\Model;
use Exception;

class Auth extends Model
{
    private static $instance;

    private function __construct()
    {
        session_start();
    }

    /**
     * @return object Representa la instancia única de la clase
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function login($username, $password, $userType)
    {
        $state = new StateOperation();

        try {

            $state->set("nivel_acceso", ["value" => $userType]);
            $state->set("usuario", ["value" => $username]);

            $user = Usuarios::findUser($username, strtolower($userType));

            if (!(bool)$user) {
                $state->add("usuario", ["message" => "Usuario no encontrado"]);
                throw new Exception(serialize($state));
            }

            if (!$this->isValidCredentials($user, $password)) {
                $state->set("password", ["message" => "Contraseña incorrecto."]);
                throw new Exception(serialize($state));
            }

            $token = bin2hex(random_bytes(32));

            $_SESSION['usuario'] = $username;
            $_SESSION['nivel_acceso'] = $userType;

            $state->set("token", $token);
        } catch (Exception $e) {

            $state = unserialize($e->getMessage());
            $state->setOperation(false);
        }

        return $state;
    }

    private function isValidCredentials($user, $password)
    {
        return password_verify($password, $user->password);
    }

    public function isAuthenticated()
    {
        if (isset($_SESSION['usuario'])) {
            return isset($_COOKIE["token"]) || !$this->logout();
        } else {
            return false;
        }
    }

    public function getUser()
    {
        return $_SESSION['usuario'] ?? null;
    }

    public function getUserType()
    {
        return $_SESSION['nivel_acceso'] ?? null;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        return true;
    }
}
