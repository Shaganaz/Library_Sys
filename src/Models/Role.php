<?php
namespace Shaganaz\Libsys\Models;

use Shaganaz\Libsys\Core\Database;

class Role
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function createRole($name)
    {
        $sql = "INSERT INTO roles (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name);
        return $stmt->execute();
    }

    public function getAllRoles()
    {
        $sql = "SELECT * FROM roles";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getRoleById($roleId)
    {
       $sql = "SELECT * FROM roles WHERE id = :id";
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':id', $roleId);
       $stmt->execute();
       return $stmt->fetch(\PDO::FETCH_ASSOC);
}
 

}
