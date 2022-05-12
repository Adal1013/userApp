<?php

namespace App\Models;

class Crud
{
    protected $table;
    protected $wheres = "";
    protected $sql = null;

    public function __construct($table = null)
    {
        $this->table = $table;
    }

    public function get()
    {
        try {
            $this->sql = "SELECT * FROM {$this->table} {$this->wheres}";
            $statement = \App\Config\DBConnection::getInstance()->prepare($this->sql);
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_OBJ);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function first()
    {
        $list = $this->get();
        if (count($list) > 0) {
            return $list[0];
        } else {
            return null;
        }
    }

    public function insert($object)
    {
        try {
            $fields = implode("`, `", array_keys($object));
            $values = ":" . implode(", :", array_keys($object));
            $this->sql = "INSERT INTO {$this->table} (`{$fields}`) VALUES ({$values})";
            $this->exec($object);
            $id = \App\Config\DBConnection::getInstance()->lastInsertId();
            return $id;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function update($object)
    {
        try {
            $fields = "";
            foreach ($object as $key => $value) {
                $fields .= "`$key`=:$key,";
            }
            $fields = rtrim($fields, ",");
            $this->sql = "UPDATE {$this->table} SET {$fields} {$this->wheres}";
            $updatedRows = $this->exec($object);
            return $updatedRows;
        } catch (\Exception $e) {
            echo $this->sql;
            echo '<pre>';
            echo json_encode($object);
            echo '<pre>';
            echo $e->getMessage();
        }
    }

    public function delete()
    {
        try {
            $this->sql = "DELETE FROM {$this->table} {$this->wheres}";
            $deletedRows = $this->exec();
            return $deletedRows;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function where($key, $condition, $value)
    {
        $this->wheres .= (strpos($this->wheres, "WHERE")) ? " AND " : " WHERE ";
        $this->wheres .= "`$key` $condition " . ((is_string($value)) ? "\"$value\"" : $value) . " ";
        return $this;
    }

    public function orWhere($key, $condition, $value)
    {
        $this->wheres .= (strpos($this->wheres, "WHERE")) ? " OR " : " WHERE ";
        $this->wheres .= "`$key` $condition " . ((is_string($value)) ? "\"$value\"" : $value) . " ";
        return $this;
    }

    private function exec($object = null)
    {
        $statement = \App\Config\DBConnection::getInstance()->prepare($this->sql);
        if (isset($object)) {
            foreach ($object as $key => $value) {
                if (empty($value)) {
                    $value = NULL;
                }
                $statement->bindValue(":$key", $value);
            }
        }
        $statement->execute();
        $this->restarValues();
        return $statement->rowCount();
    }

    private function restarValues()
    {
        $this->wheres = "";
        $this->sql = null;
    }
}
