<?php
session_save_path(sys_get_temp_dir());
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Shaganaz\Libsys\Controllers\AuthController;
use Shaganaz\Libsys\Controllers\DashboardController;
use Shaganaz\Libsys\Controllers\BookController;
use Shaganaz\Libsys\Controllers\SuperAdminController;
use Shaganaz\Libsys\Core\View;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$authController = new AuthController();
$dashboardController = new DashboardController();
$bookController = new BookController();
$superadminController = new SuperAdminController();

if ($uri === '/login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->login();
    } else {
        $authController->showLoginForm();
    }
}



elseif ($uri === '/register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->register();
    } else {
        $authController->showRegisterForm();
    }
}

elseif ($uri === '/superadmin/select-dashboard') {
    $user = $_SESSION['user'] ?? null;

    if ($user && $user['role_name'] === 'super_admin') {

        $dashboardController->dashboard('select');
    } else {
        header('Location: /login');
        exit;
    }
} elseif ($uri === '/superadmin/dashboard') {
    $user = $_SESSION['user'] ?? null;

    if ($user && $user['role_name'] === 'super_admin') {
        $dashboardController->dashboard('superadmin');
    } else {
        header('Location: /superadmin/select-dashboard');
        exit;
    }
} elseif ($uri === '/user/dashboard') {
    $user = $_SESSION['user'] ?? null;
    if ($user) {
        $dashboardController->dashboard('user');
    } else {
        header('Location: /login');
        exit;
    }
} elseif (
    $uri === '/superadmin/create-user' ||
    $uri === '/superadmin/create-role' ||
    $uri === '/superadmin/list-users'
) {
    $user = $_SESSION['user'] ?? null;

    if (!$user || $user['role_name'] !== 'super_admin') {
        header('Location: /login');
        exit;
    }

    if ($uri === '/superadmin/create-user') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $superadminController->createUser();
        } else {
            $superadminController->showCreateUserForm();
        }
    } elseif ($uri === '/superadmin/create-role') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $superadminController->createRole();
        } else {
            $superadminController->createRoleForm();
        }
    } elseif ($uri === '/superadmin/list-users') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $superadminController->updateUserRole();
        } else {
            $superadminController->listUsers();
        }
    }
} 
elseif ($uri === '/user/list-books') {
    $user = $_SESSION['user'] ?? null;
    if ($user && in_array($user['role_name'], ['student', 'teacher', 'librarian','super_admin'])) {
        $bookController->listBooks();
    } else {
        header('Location: /login');
        exit;
    }
} elseif ($uri === '/user/create-book') {
    $user = $_SESSION['user'] ?? null;
    if ($user && in_array($user['role_name'], ['student', 'teacher', 'librarian','super_admin'])) {
        $bookController->createBook();
    } else {
        header('Location: /login');
        exit;
    }
}else {
    http_response_code(404);
    View::render('errors/404', ['uri' => $uri]);
}
