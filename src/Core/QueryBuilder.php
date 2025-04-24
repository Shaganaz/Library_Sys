<?php
namespace Shaganaz\Libsys\Core;
use PDO;
class QueryBuilder
{
    protected $pdo;
    public function __construct()
    {
        $this->pdo=Database::getInstance();
       
    }
    public function createTable($table, $columns)
{
    $cols = [];
    $foreigns = [];
    foreach ($columns as $name => $definition) {
        if (stripos($definition, 'foreign key') !== false) {
            $foreigns[] = $definition;
        } else {
            $cols[] = "`$name` $definition";
        }
    }
    $allColumns = array_merge($cols, $foreigns);

    $sql = "CREATE TABLE IF NOT EXISTS `$table` (" . implode(', ', $allColumns) . ")";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute();
}    
public function insert($table, $data)
{
    $columns = implode(", ", array_keys($data)); 
    $values = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO `$table` ($columns) VALUES ($values)";
    $stmt = $this->pdo->prepare($sql);
    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    return $stmt->execute();
 }
}
