<?php

namespace Models;

class Model
{
    protected static $connection;
    protected $table = "";
    protected $primaryKey = "";
    public $fillable = [];
    public $hiddens = [];

    public static function createConnection()
    {
        $instance = DataBase::getInstance();
        self::$connection = $instance->getConnection();
    }

    public static function find($id)
    {
        $model = new static();
        return $model->where($model->primaryKey, '=', $id)->first();
    }

    public static function where($column, $operator, $value)
    {
        $model = new static();
        return $model->newQuery()->where($column, $operator, $value);
    }

    public function newQuery()
    {
        // Devuelve una nueva instancia de QueryBuilder para la tabla actual.
        return new QueryBuilder($this->table, $this);
    }
}
