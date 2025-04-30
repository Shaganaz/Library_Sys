<?php
namespace Shaganaz\Libsys\Controllers;
use Shaganaz\Libsys\Models\User; 
class AuthController
{
    public function showLoginForm()
    {
        require_once __DIR__ . '/../Views/Auth/login.php';
    }
    public function showRegisterForm()
    {
        require_once __DIR__ . '/../Views/Auth/register.php';
    }



    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
    
        
        if (empty($email) || empty($password)) {
            $message = ['success' => false, 'message' => 'Email and password are required.'];
            return $this->respond($isAjax, $message);
        }
    
    
        $userModel = new User();
        $user = $userModel->getUserByEmail($email);
    
        if (!$user || !password_verify($password, $user['password'])) {
            $message = ['success' => false, 'message' => 'Invalid email or password.'];
            return $this->respond($isAjax, $message);
        }
    
    
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role_id' => $user['role_id'],
            'role_name' => $user['role_name']
        ];
    
        $redirectUrl = ($user['role_name'] === 'super_admin') ? '/superadmin/select-dashboard' : '/user/dashboard';
        $message = ['success' => true, 'redirect' => $redirectUrl];
    
        return $this->respond($isAjax, $message);
    }
}
   
    

    public function logout() {
        session_start();  
        session_unset();  
        session_destroy();  
        header("Location: /login");  
        exit;
    }


    public function register()
    {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $message = ['success' => false, 'message' => 'All fields are required'];
            return $this->respond($isAjax, $message);
        }
        
        $userModel = new User();
        $existingUser = $userModel->getUserByEmail($email);
        
        if ($existingUser) {
            $message = ['success' => false, 'message' => 'Email already exists.'];
            return $this->respond($isAjax, $message);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userModel->createUser($name, $email, $hashedPassword);
        

        $message = ['success' => true, 'message' => 'Registration successful. Please login.'];
        if (!$isAjax) {
            $message['redirect'] = '/login';
        }
        return $this->respond($isAjax, $message);
    }
}


    private function respond($isAjax, $message)
    {
    if ($isAjax) {
    
        header('Content-Type: application/json');
        echo json_encode($message);
    } else {
         
        $redirectUrl = $message['redirect'] ?? '/login';
        header("Location: $redirectUrl?error={$message['message']}");
    }
    exit;
}

}
