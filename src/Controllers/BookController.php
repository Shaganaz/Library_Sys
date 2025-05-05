<?php
namespace Shaganaz\Libsys\Controllers;

use Shaganaz\Libsys\Core\View;
use Shaganaz\Libsys\Models\Book;

class BookController
{

 
    public function listBooks()
{
    $bookModel = new Book();
    $books = [];

    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $query = trim($_GET['search']);
        $books = $bookModel->searchBooks($query);
    } else {
        $books = $bookModel->getAllBooks(); 
    }

    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

    View::render('user/list-books', [
        'books' => $books,
        'bookModel' => $bookModel,
        'isAjax' => $isAjax
    ]);
}

    


    public function createBook(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $isbn = $_POST['isbn'] ?? '';

        if ($id && $title && $author && $isbn) {
            $bookModel = new Book();
            $bookModel->addBook([
                'id' => $id,
                'title' => $title,
                'author' => $author,
                'isbn' => $isbn
            ]);
            echo json_encode(['success' => true, 'message' => 'Book created']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }
    } else {
        View::render('user/create-book');
    }
}



public function editBook()
{
    $bookModel = new Book();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];

        if ($id && $title && $author && $isbn) {
            $bookModel->updateBook([
                'id' => $id,
                'title' => $title,
                'author' => $author,
                'isbn' => $isbn
            ]);
            echo json_encode(['success' => true, 'message' => 'Book updated successfully.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $book = $bookModel->getBookById($id);
            if ($book) {
                View::render('/user/edit-book', [
                    'book' => $book
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Book not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Book ID is required']);
        }
        exit;
    }
}



public function deleteBook()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? null;

        if ($id) {
            $bookModel = new Book();
            $bookModel->deleteBook($id);
            echo json_encode(['status' => 'success', 'message' => 'Book deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Book ID is required.']);
        }
    } else {
        http_response_code(405); 
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    }
}




public function requestBook()
{
    header('Content-Type: application/json');
    $user = $_SESSION['user'] ?? null;
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }
    $bookModel = new Book();
    $userId = $user['id'];
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $isbn = $_POST['isbn'] ?? null;
    if (trim($title) === '' || trim($author) === '') {
        echo json_encode(['success' => false, 'message' => 'Title and Author are required.']);
        exit;
    }
    $bookModel->requestBook($userId, $title, $author, $isbn);
    echo json_encode(['success' => true]);
    exit;
}




public function viewBookRequests()
    {
        $user = $_SESSION['user'] ?? null;
        if ($user['role_name'] !== 'librarian') {
            header("Location: /user/dashboard");
            exit();
        }

        $bookModel = new Book();
        $requests = $bookModel->getPendingRequests();


        View::render('librarian/view-requests', ['requests' => $requests]);
    }



    public function updateRequestStatus()
{
    $user = $_SESSION['user'] ?? null;
    if ($user['role_name'] !== 'librarian') {
        http_response_code(403);
        echo "Unauthorized";
        exit();
    }

    if (isset($_POST['request_id'], $_POST['status'])) {
        $requestId = $_POST['request_id'];
        $status = $_POST['status'] === 'approved' ? 'awaiting_super_admin' : 'rejected';

        $bookModel = new Book();
        $bookModel->updateRequestStatus($requestId, $status);

        http_response_code(200);
        echo "Success";
        exit();
    }

    http_response_code(400);
    echo "Invalid request";
    exit();
}




    public function viewRequests()
    {
    $user = $_SESSION['user'] ?? null;
    if ($user['role_name'] !== 'super_admin') {
        header("Location: /superadmin/dashboard");
        exit();
    }
    $bookModel = new Book();
    $requests = $bookModel->getAwaitingSuperAdminRequests(); 

   View::render('superadmin/view-requests', ['requests' => $requests]);

    }



    public function approveRequest($requestId)
    {
        $bookModel = new Book();
        
        
        $result = $bookModel->approveRequest($requestId);

        if ($result) {
            header('Location: /superadmin/select-dashboard');
            exit();
        } else { 
            echo "Failed to approve request.";
        }
    }


    public function rejectRequest($requestId)
    {
        $bookModel = new Book();
        $result = $bookModel->rejectRequest($requestId);

        if ($result) {
            header('Location: /superadmin/select-dashboard');
            exit();
        } else {
            echo "Failed to reject request.";
        }
    }



    public function borrowBook()
    {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-type:appplication/json');
       
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'unauthenticated']);
            exit;
        }

        
        $bookId = $_POST['book_id'] ?? null;
        if (!$bookId) {
            echo json_encode(['status' => 'error', 'message' => 'Book ID missing']);
            exit;
        }

        
        $bookModel = new Book();     
        $borrowedBook = $bookModel->isBookBorrowed($bookId);
        
        if ($borrowedBook) {
            
            echo json_encode(['status' => 'already_borrowed']);
            exit;
        }      
        $borrowDate = date('Y-m-d H:i:s');
        $returnDate = date('Y-m-d H:i:s', strtotime('+10 days'));
        $userId = $_SESSION['user']['id'];       
        $bookModel->borrowBook($userId, $bookId, $borrowDate, $returnDate);       
        $bookModel->updateBookStatus($bookId, 'borrowed');       
        echo json_encode(['status' => 'success']);
        exit;
    }
    }

   
}






