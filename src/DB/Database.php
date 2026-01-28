<?php

namespace App\DB;
use PDO;

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (!self::$instance) {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $db   = $_ENV['DB_NAME'] ?? 'blog';
            $user = $_ENV['DB_USER'] ?? 'root';
            $pass = $_ENV['DB_PASS'] ?? '';
            $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return self::$instance;
    }
}