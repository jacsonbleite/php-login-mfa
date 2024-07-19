<?php

namespace App\Database;

use PDO;
use PDOException;
use PDOStatement;

class DatabaseConnection
{
    private static $driver;
    private static $host;
    private static $name;
    private static $user;
    private static $pass;
    private static $port;    
    private $connection;

    public function __construct(PDO $connection = null)
    {
        if ($connection) {
            $this->connection = $connection;
        } else {
            $this->setConnection();
        }
    }

    public static function config($driver, $host, $name, $user, $pass, $port)
    {
        self::$driver = $driver;
        self::$host = $host;
        self::$name = $name;
        self::$user = $user;
        self::$pass = $pass;
        self::$port = $port;
    }

    private function setConnection()
    {
        try {
            $this->connection = new PDO(self::$driver . ":host=" . self::$host . ";dbname=" . self::$name . ";port=" . self::$port, self::$user, self::$pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }

        return $this->connection;
    }

    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetch($sql, $params = [])
    {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function execute($sql, $params = []): PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    private function formatJson($data)
    {
        return json_encode($data);
    }

    public function returnJson($data)
    {
        // header('Content-Type: application/json');
        echo $this->formatJson($data);
        exit;
    }
}
