<?php

namespace Models;

use Models\Model;

class Empleados extends Model
{
    protected $primaryKey = "id";
    protected $table = "empleados";
    public $fillable = ["id", "nombre", "nivel_acceso"];
    public $hiddens = [];
}
