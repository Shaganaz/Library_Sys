<?php
namespace Shaganaz\Libsys\Controllers;

use Shaganaz\Libsys\Models\Role;
use Shaganaz\Libsys\Models\User;
use Shaganaz\Libsys\Core\View;
use Shaganaz\Libsys\Core\Database;
use PDO;
use PDOException;
class SuperAdminController{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function showCreateUserForm()
    {
        $roleModel = new Role();
        $roles = $roleModel->getAllRoles();
        View::render('superadmin/create-user', ['roles' => $roles]);
    }

    public function createUser()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role_id = $_POST['role_id'] ?? 4;

        if ($name && $email && $password && $role_id) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $userModel = new User();
            $userModel->createUser($name, $email, $hashedPassword, $role_id);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => "User '$name' created successfully."
                ]);
                return;
            }

            header('Location: /superadmin/dashboard');
            exit;
        } else {
            $error = "All fields are required.";

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $error
                ]);
                return;
            }

            echo $error;
        }
    } else {
        $this->showCreateUserForm();
    }
}



    public function assignRoles()
    {
        View::render('superadmin/assign-roles');
    }

    
    public function createRoleForm()
    {

    View::render('superadmin/create-role'); 
    }



    public function createRole()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log('POST Data: ' . print_r($_POST, true));
        $name = trim($_POST['name']);

        if (!empty($name)) {
            $roleModel = new Role();
            $roleModel->createRole($name);
            $successMessage = "New role '$name' has been created!";

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => $successMessage
                ]);
                return;
            }

            $_SESSION['success_message'] = $successMessage;
            header('Location: /superadmin/create-role');
            exit;
        } else {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Role name is required.'
                ]);
                return;
            }

            View::render('superadmin/create-role', ['error' => 'Role name is required.']);
        }
    } else {
        View::render('superadmin/create-role');
    }
}



    public function listUsers()
    {
    $userModel = new User();
    $users = $userModel->getAllUsersWithRoles();

    $roleModel = new Role();
    $roles = $roleModel->getAllRoles();
    
    $updatedUsers = $_SESSION['updated_users'] ?? [];
    foreach ($users as &$user) {
        if (isset($updatedUsers[$user['id']])) {
            $user['updated_role'] = $updatedUsers[$user['id']];
        } else {
            $user['updated_role'] = '';
        }
    }
    View::render('superadmin/list-users', [
        'users' => $users,
        'roles' => $roles
    ]);
    }



    public function updateUserRole()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_POST['user_id'] ?? null;
        $newRoleId = $_POST['role_id'] ?? null;

        if ($userId && $newRoleId) {
            $userModel = new User();
            $roleModel = new Role();

            $oldUser = $userModel->getUserByIdWithRole($userId);
            $newRole = $roleModel->getRoleById($newRoleId);

            $userModel->updateUserRole($userId, $newRoleId);
            $_SESSION['updated_users'][$userId] = $newRole['name'];

            $message = "{$oldUser['name']} ({$oldUser['email']})'s role has been updated from '{$oldUser['role_name']}' to '{$newRole['name']}'.";

            if (
                !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            ) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'updated_role' => $newRole['name'],
                    'message' => $message
                ]);
                return;
            }

            $users = $userModel->getAllUsersWithRoles();
            $roles = $roleModel->getAllRoles();
            foreach ($users as &$user) {
                $user['updated_role'] = $user['id'] == $userId ? $newRole['name'] : '';
            }

            View::render('superadmin/list-users', [
                'users' => $users,
                'roles' => $roles,
                'message' => $message
            ]);
        }
    }
}



public function deleteUser($userId)
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['role_name'] !== 'super_admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        return;
    }

    try {
        $query = "SELECT COUNT(*) FROM book_requests WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $requestCount = $stmt->fetchColumn();

        $query = "SELECT COUNT(*) FROM borrowed_books WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $borrowCount = $stmt->fetchColumn();

        if ($requestCount > 0 || $borrowCount > 0) {
            echo json_encode(['success' => false, 'message' => 'User has active records. Cannot delete.']);
            return;
        }

        $query = "DELETE FROM users WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}


public function showDeleteUserForm($userId)
{

    if ($_SESSION['role'] != 'super_admin') {
        echo "You do not have permission to delete users.";
        return;
    }

    $query = "SELECT name, email FROM users WHERE id = :user_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();

    if (!$user) {
        echo "User not found.";
        return;
    }


    View::render('superadmin/delete-user',['user' =>$user]);
}

}