<?php
namespace Shaganaz\Libsys\Controllers;
use Shaganaz\Libsys\Core\View;
use Shaganaz\Libsys\Models\Book;
use Shaganaz\Libsys\Core\Database;
class DashboardController
{
    private function getCurrentUser()
    {
        return $_SESSION['user'] ?? null;
    }
    public function dashboard($dashboardType = 'select')
{
    $user = $this->getCurrentUser();
    if (!$user) {
        echo "User not logged in.";
        exit;
    }

    $bookModel = new Book();

    if ($user['role_name'] === 'super_admin') {
        if ($dashboardType === 'select') {
            View::render('superadmin/select-dashboard');
        } elseif ($dashboardType === 'superadmin') {
            $requests = $bookModel->getPendingRequests(); 
            View::render('superadmin/dashboard', ['requests' => $requests]);
        } elseif ($dashboardType === 'user') {
            View::render('user/dashboard');
        }
    } elseif (in_array($user['role_name'], ['student', 'teacher', 'librarian'])) {
        if ($dashboardType === 'user') {
            View::render('user/dashboard');
        } else {
            header('Location: /user/dashboard');
            exit;
        }
    } else {
        View::render('errors/403', ['message' => 'Unauthorized role.']);
    }
}



public function showDashboard() {
    $userId = $_SESSION['user_id'];
    
    $db = Database::getInstance();
    $query = "SELECT role FROM users WHERE id = :userId";
    $stmt = $db->prepare($query);
    $stmt->execute([':userId' => $userId]);
    $user = $stmt->fetch();
    
    $userRequests = [];
    if ($user['role'] == 'librarian') {
        $query = "SELECT * FROM book_requests WHERE user_id = :userId";
        $stmt = $db->prepare($query);
        $stmt->execute([':userId' => $userId]);
        $userRequests = $stmt->fetchAll();
    }

    View::render('user/dashboard', ['userRequests' => $userRequests]);
}

}




