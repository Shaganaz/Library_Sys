<?php
namespace Shaganaz\Libsys\Core;
use PDO;
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=library_system';
        $username = 'root';
        $password = 'Shaganaz@123';
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }
}
