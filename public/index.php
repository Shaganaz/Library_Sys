<?php
session_save_path(sys_get_temp_dir());
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Shaganaz\Libsys\Controllers\AuthController;
use Shaganaz\Libsys\Controllers\DashboardController;
use Shaganaz\Libsys\Controllers\BookController;
use Shaganaz\Libsys\Controllers\SuperAdminController;
use Shaganaz\Libsys\Core\View;
use Shaganaz\Libsys\Models\Book;
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
        exit;
    }
}

if ($_SERVER['REQUEST_URI'] === '/logout') {
    $controller = new \Shaganaz\Libsys\Controllers\AuthController();
    $controller->logout();
} 

elseif ($uri === '/register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->register();
    } else {
        $authController->showRegisterForm();
    }
} 


elseif ($uri === '/superadmin/select-dashboard') {
    $authMiddleware->handle();
    $user = $_SESSION['user'] ?? null;

    if ($user && $user['role_name'] === 'super_admin') {

        $dashboardController->dashboard('select');
    } else {
        header('Location: /login');
        exit;
    }
} 


elseif ($uri === '/superadmin/dashboard') {
    $authMiddleware->handle();
    $user = $_SESSION['user'] ?? null;
    if ($user && $user['role_name'] === 'super_admin') {
        $dashboardController->dashboard('superadmin');
    } else {
        header('Location: /superadmin/select-dashboard');
        exit;
    }
} 


elseif ($uri === '/user/dashboard') {
    $authMiddleware->handle();
    $user = $_SESSION['user'] ?? null;
    if ($user) {
        $dashboardController->dashboard('user');
    } else {
        header('Location: /login');
        exit;
    }
} 


elseif (
    $uri === '/superadmin/create-user' ||
    $uri === '/superadmin/create-role' ||
    $uri === '/superadmin/list-users' ||
    $uri === '/superadmin/delete-user') 
    {
    $authMiddleware->handle();
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
    } 
    
    
    elseif ($uri === '/superadmin/create-role') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $superadminController->createRole();
        } else {
            $superadminController->createRoleForm();
        }
    } 
    
    
    elseif ($uri === '/superadmin/list-users') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $superadminController->updateUserRole();
        } else {
            $superadminController->listUsers();
        }
    } 
    
    
    elseif ($uri === '/superadmin/delete-user') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id']; 
            $superadminController->deleteUser($userId); 
        }
    }
    
} 


elseif ($uri === '/user/list-books') {
    $authMiddleware->handle();
    $user = $_SESSION['user'] ?? null;
    if ($user && in_array($user['role_name'], ['student', 'teacher', 'librarian', 'super_admin'])) {
        $bookController->listBooks();
    } else {
        header('Location: /login');
        exit;
    }
} 


elseif ($uri === '/user/create-book') {
    $authMiddleware->handle();
    $user = $_SESSION['user'] ?? null;
    if ($user && in_array($user['role_name'], ['student', 'teacher', 'librarian', 'super_admin'])) {
        $bookController->createBook();
    } else {
        header('Location: /login');
        exit;
    }
} 


elseif ($uri === '/user/edit-book') {
    $authMiddleware->handle();
    $user = $_SESSION['user'] ?? null;
    if ($user && in_array($user['role_name'], ['librarian', 'super_admin'])) {
        $bookController->editBook();
    } else {
        header('Location: /login');
        exit;
    }
} 


elseif ($uri === '/user/delete-book') {
    $authMiddleware->handle();
    $user = $_SESSION['user'] ?? null;
    if ($user && in_array($user['role_name'], ['librarian', 'super_admin'])) {
        $bookController->deleteBook();
    } else {
        header('Location: /login');
        exit;
    }
} 


elseif ($uri === '/user/request-book') {
    $user = $_SESSION['user'] ?? null;
    $authMiddleware->handle();
    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookController->requestBook();
        } else {
            View::render('user/request-book');
        }
    } else {
        header('Location: /login');
        exit;
    }
} 


elseif ($uri === '/librarian/view-requests') {
    $authMiddleware->handle();
    $user = $_SESSION['user'] ?? null;
    if ($user && $user['role_name'] === 'librarian') {
        $bookController->viewBookRequests();
    } else {
        header('Location: /login');
        exit();
    }
} 


elseif ($uri === '/librarian/request-status') {
    $authMiddleware->handle();
    $user = $_SESSION['user'] ?? null;
    if ($user && $user['role_name'] === 'librarian') {
        $bookController->updateRequestStatus();
    } else {
        header('Location: /login');
        exit();
    }
} 


elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/superadmin/view-requests') {
    $controller = new BookController();
    $controller->viewRequests();  
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/superadmin/request-status') {
    $requestId = $_POST['request_id'];
    $status = $_POST['status'];
    $bookModel = new Book();
    if ($status === 'approved') {
        $bookModel->approveRequest($requestId);
    } elseif ($status === 'rejected') {
        $bookModel->rejectRequest($requestId);
    }
    header('Location: /superadmin/view-requests');
    exit();
} 



elseif ($uri === '/user/borrow-book' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookController->borrowBook();
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }
}




else {
    http_response_code(404);
    echo "<h1>404 Page Not Found</h1>";
    echo "<p>The requested page could not be found. Please check the URL and try again.</p>";
    echo "<p><a href='/'>Go back to the homepage</a></p>";
}
