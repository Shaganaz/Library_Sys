<?php
namespace Shaganaz\Libsys\Controllers;

use Shaganaz\Libsys\Models\Role;
use Shaganaz\Libsys\Models\User;
use Shaganaz\Libsys\Core\View;
class SuperAdminController{

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
                header('Location: /superadmin/dashboard');
                exit;
            } else {
                echo "All fields are required.";
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
            $_SESSION['success_message'] = "New role '$name' has been created!";
            header('Location: /superadmin/create-role');
            exit;
        } else {
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
            $users = $userModel->getAllUsersWithRoles();
            $roles = $roleModel->getAllRoles();
            foreach ($users as &$user) {
                if ($user['id'] == $userId) {
                    $user['updated_role'] = $newRole['name']; 
                } else {
                    $user['updated_role'] = ''; 
                }
            }
            View::render('superadmin/list-users', [
                'users' => $users,
                'roles' => $roles,
                'message' => $message
            ]);
        }
    }
}
}