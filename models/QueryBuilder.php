<?php

namespace Models;

use PDO;

class QueryBuilder
{
    protected $table;
    protected $conditions = [];
    protected $model;

    public function __construct($table, $model)
    {
        $this->table = $table;
        $this->model = $model;
    }

    public function where($column, $operator, $value)
    {
        $validOperators = ['=', '!=', '<', '>', '<=', '>='];
        if (in_array($operator, $validOperators)) $this->conditions[] = compact('column', 'operator', 'value');
        return $this;
    }

    /** 
     * @param bool $strict Indica si se deben considerar los campos visibles del modelo.
     */
    public function get($strict = true)
    {
        $query = "{$this->table} WHERE " . $this->buildWhere();
        $statement = $this->buildQuery($query, $strict);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /** 
     * @param bool $strict Indica si se deben considerar los campos visibles del modelo.
     */
    public function first($strict = true)
    {
        $query = "{$this->table} WHERE " . $this->buildWhere() . " LIMIT 1";
        $statement = $this->buildQuery($query, $strict);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    protected function buildWhere()
    {
        $conditions = [];
        foreach ($this->conditions as $clause) {
            $conditions[] = "{$clause['column']} {$clause['operator']} :{$clause['column']}";
        }
        return implode(' AND ', $conditions);
    }

    protected function buildQuery($query, $strict)
    {
        if ($strict) {
            $filters = array_diff($this->model->fillable, $this->model->hiddens);
            $query = "SELECT " . implode(",", $filters) . " FROM " . $query;
        } else {
            $query = "SELECT * FROM " . $query;
        }

        $connection = DataBase::getInstance()->getConnection();
        $statement =  $connection->prepare($query);
        foreach ($this->conditions as $condition) {
            $statement->bindParam(":{$condition['column']}", $condition['value']);
        }
        $statement->execute();
        return $statement;
    }
}
