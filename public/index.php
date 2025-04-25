<?php
session_save_path(sys_get_temp_dir());
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Shaganaz\Libsys\Controllers\AuthController;
use Shaganaz\Libsys\Controllers\DashboardController;
use Shaganaz\Libsys\Controllers\BookController;
use Shaganaz\Libsys\Controllers\SuperAdminController;
use Shaganaz\Libsys\Controllers\LibrarianController;
use Shaganaz\Libsys\Core\View;
use Shaganaz\Libsys\Middleware\AuthMiddleware;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$authController = new AuthController();
$dashboardController = new DashboardController();
$bookController = new BookController();
$superadminController = new SuperAdminController();
$authMiddleware = new AuthMiddleware();
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
    $authMiddleware->handle();

    if ($user && $user['role_name'] === 'super_admin') {

        $dashboardController->dashboard('select');
    } else {
        header('Location: /login');
        exit;
    }
} elseif ($uri === '/superadmin/dashboard') {
    $user = $_SESSION['user'] ?? null;
    $authMiddleware->handle();
    if ($user && $user['role_name'] === 'super_admin') {
        $dashboardController->dashboard('superadmin');
    } else {
        header('Location: /superadmin/select-dashboard');
        exit;
    }
} elseif ($uri === '/user/dashboard') {
    $user = $_SESSION['user'] ?? null;
    $authMiddleware->handle();
    if ($user) {
        $dashboardController->dashboard('user');
    } else {
        header('Location: /login');
        exit;
    }
} elseif (
    $uri === '/superadmin/create-user' ||
    $uri === '/superadmin/create-role' ||
    $uri === '/superadmin/list-users' ||
    $uri === '/superadmin/delete-user'
) {
    $user = $_SESSION['user'] ?? null;
    $authMiddleware->handle();

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
    } elseif ($uri === '/superadmin/delete-user') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $superadminController->deleteUser(); 
        }
    }
    

} 
elseif ($uri === '/user/list-books') {
    $user = $_SESSION['user'] ?? null;
    $authMiddleware->handle();
    if ($user && in_array($user['role_name'], ['student', 'teacher', 'librarian','super_admin'])) {
        $bookController->listBooks();
    } else {
        header('Location: /login');
        exit;
    }
} elseif ($uri === '/user/create-book') {
    $user = $_SESSION['user'] ?? null;
    $authMiddleware->handle();
    if ($user && in_array($user['role_name'], ['student', 'teacher', 'librarian','super_admin'])) {
        $bookController->createBook();
    } else {
        header('Location: /login');
        exit;
    }
} elseif ($uri==='/user/edit-book') {
    $user = $_SESSION['user'] ?? null;
    $authMiddleware->handle();
    if ($user && in_array($user['role_name'], ['librarian', 'super_admin'])) {
        $bookController->editBook();
    } else {
        header('Location: /login');
        exit;
    }
} elseif ($uri === '/user/delete-book') {
    $user = $_SESSION['user'] ?? null;
    $authMiddleware->handle();
    if ($user && in_array($user['role_name'], ['librarian', 'super_admin'])) {
        $bookController->deleteBook();
    } else {
        header('Location: /login');
        exit;
    }
} elseif ($uri === '/user/request-book') {
    $user = $_SESSION['user'] ?? null;
    $authMiddleware->handle();
    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookController->requestBook(); 
        }

        else {
            View::render('user/request-book');  
        }
    } else {
        header('Location: /login');
        exit;
    }
} elseif ($uri === '/superadmin/pending-requests' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $authMiddleware->handle();
    $superadminController->viewPendingRequests();
}

elseif ($uri === '/superadmin/approve-request' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_SESSION['user'] ?? null;
    $authMiddleware->handle();
    if ($user && $user['role_name'] === 'super_admin') {
        $superadminController->approveRequest($_POST['request_id']);
    } else {
        header('Location: /login');
        exit;
    }
} elseif ($uri === '/superadmin/reject-request' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_SESSION['user'] ?? null;
    $authMiddleware->handle();
    if ($user && $user['role_name'] === 'super_admin') {
        $superadminController->rejectRequest($_POST['request_id']);
    } else {
        header('Location: /login');
        exit;
    }

} elseif ($uri === '/librarian/pending-requests') {
        $user = $_SESSION['user'] ?? null;
        $authMiddleware->handle();
        if ($user && $user['role_name'] === 'librarian') {
            $librarianController = new  LibrarianController();
            $librarianController->viewPendingRequests();
        } else {
            header('Location: /login');
            exit;
        }
    }

    elseif (preg_match('#^/librarian/approve/(\d+)$#', $uri, $matches)) {
        $user = $_SESSION['user'] ?? null;
        $authMiddleware->handle();
        if ($user && $user['role_name'] === 'librarian') {
            $requestId = $matches[1];
            $librarianController = new LibrarianController();
            $librarianController->approveRequest($requestId);
        } else {
            header('Location: /login');
            exit;
        }
    }
    elseif (preg_match('#^/librarian/reject/(\d+)$#', $uri, $matches)) {
        $user = $_SESSION['user'] ?? null;
        if ($user && $user['role_name'] === 'librarian') {
            $requestId = $matches[1];
            $librarianController = new LibrarianController();
            $librarianController->rejectRequest($requestId);
        } else {
            header('Location: /login');
            exit;
        }
    }
else {
    http_response_code(404);
    View::render('errors/404', ['uri' => $uri]);
}
