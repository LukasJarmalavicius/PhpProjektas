<?php

namespace classes\database;

use PDO;
use PDOException;

class DbConnection
{

    private const string HOST = "localhost";
    private const string USER = "roots";
    private const string PASSWORD = "";
    private const string DB = "test";

    public static function connection(): PDO
    {

        try {
            $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DB;
            $conn = new PDO($dsn, self::USER, self::PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $conn;
    }
}