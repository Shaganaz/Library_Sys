<?php
namespace Shaganaz\Libsys\Controllers;
use Shaganaz\Libsys\Models\User; 
use Shaganaz\Libsys\Models\Role; 
use Shaganaz\Libsys\Core\View;
class AuthController
{
    public function showLoginForm()
    {
        require_once __DIR__ . '/../Views/default/login.php';
    }
    public function showRegisterForm()
    {
        require_once __DIR__ . '/../Views/default/register.php';
    }



    public function login()
    {       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            if (empty($email) || empty($password)) {
                header('Location: /login?error=empty_fields');
                exit;
            }
            $userModel = new User();
            $user = $userModel->getUserByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'role_name' => $user['role_name'] 
                ];
                if ($user['role_name'] == 'super_admin') { 
                    header('Location: /superadmin/select-dashboard'); 
                } else {
                    header('Location: /user/dashboard'); 
                }
                exit;
            }
            
        }
    }



    public function register()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if (empty($name) || empty($email) || empty($password)) {
            header('Location: /register?error=empty_fields');
            exit;
        }
        $userModel = new User();
        $existingUser = $userModel->getUserByEmail($email);
        if ($existingUser) {
            header('Location: /register?error=email_exists');
            exit;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userModel->createUser($name, $email, $hashedPassword);
        header('Location: /login?success=registration_success');
        exit;
    }
}
}
