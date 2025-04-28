<?php
namespace Shaganaz\Libsys\Models;
use Shaganaz\Libsys\Core\Database;
use PDO;
class User
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    public function getUserByEmail($email)
    {
        $sql = "
        SELECT users.*, roles.name AS role_name
        FROM users
        JOIN roles ON users.role_id = roles.id
        WHERE users.email = :email
        LIMIT 1
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function userExists($email)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
  
    public function createUser($name, $email, $password,$roleId=2)
    { 
        $sql = "INSERT INTO users (name, email, password, role_id) VALUES (:name, :email, :password, :role_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, \PDO::PARAM_STR);
        $stmt->bindParam(':role_id', $roleId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function getAllUsersWithRoles()
{
    $query = "SELECT users.id, users.name, users.email, roles.name AS role_name, roles.id AS role_id
              FROM users
              JOIN roles ON users.role_id = roles.id";
    return $this->db->query($query)->fetchAll();
}
public function updateUserRole($userId, $newRoleId)
{
    $stmt = $this->db->prepare("UPDATE users SET role_id = :role_id WHERE id = :user_id");
    $stmt->bindParam(':role_id', $newRoleId);
    $stmt->bindParam(':user_id', $userId);
    return $stmt->execute();
}

public function getUserByIdWithRole($userId)
{
    $query = "SELECT users.id, users.name, users.email, roles.name AS role_name
              FROM users
              JOIN roles ON users.role_id = roles.id
              WHERE users.id = :user_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetch();
}

}









