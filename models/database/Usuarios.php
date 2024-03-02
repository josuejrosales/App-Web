<?php

namespace Models;

use Models\Model;

class Usuarios extends Model
{
    protected $primaryKey = "id";
    protected $table = "usuarios";
    public $fillable = ["id", "usuario"];
    public $hiddens = [];

    public static function findUser($userName, $userType)
    {
        if ($user = Usuarios::where("usuario", "=", $userName)->first(false)) {
            if ($empleado = Empleados::where("id", "=", $user["id"])->where("nivel_acceso", "=", $userType)->first(false)) {
                $user["nivel_acceso"] = $empleado["nivel_acceso"];
                return (object)$user;
            }
        }
    }
}
