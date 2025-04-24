<?php
namespace Shaganaz\Libsys\Controllers;
use Shaganaz\Libsys\Core\View;
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
        if ($user['role_name'] === 'super_admin') {
            if ($dashboardType === 'select') {
                View::render('superadmin/select-dashboard');
            } elseif ($dashboardType === 'superadmin') {
                View::render('superadmin/dashboard');
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
}






