<?php

namespace App\Config;

class DBConnection
{
    private static $instance = null;

    private function __construct()
    {
        try {
            $driver = getenv('DB_CONNECTION');
            $host = getenv('DB_HOST');
            $port = getenv('DB_PORT');
            $db = getenv('DB_DATABASE');
            $charset = getenv('DB_ENCODE');
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD');
            $url = "{$driver}:host={$host}:{$port};"
                . "dbname={$db};charset={$charset}";
            static::$instance = new \PDO($url, $username, $password);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function getInstance(): \PDO
    {
        if (!isset(static::$instance)) {
            new static();
        }
        return static::$instance;
    }
}
