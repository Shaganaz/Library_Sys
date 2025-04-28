<?php
namespace Shaganaz\Libsys\Controllers;

use Shaganaz\Libsys\Core\View;
use Shaganaz\Libsys\Models\Book;

class BookController
{
    public function listBooks()
{
    $bookModel = new Book();
    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $query = trim($_GET['search']);
        $books = $bookModel->searchBooks($query);
    } else {
        $books = $bookModel->getAllBooks(); 

    }
    View::render('user/list-books', ['books' => $books, 'bookModel'=>$bookModel]);
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
            header('Location: /user/list-books');
            exit;
        } else {
            echo "All fields are required.";
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
            header('Location: /user/list-books');
            exit;
        } else {
            echo "All fields are required.";
        }
    } else {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $book = $bookModel->getBookById($id);
            if ($book) {
                View::render('user/edit-book', ['book' => $book]);
            } else {
                echo "Book not found.";
            }
        } else {
            echo "Book ID is required.";
        }
    }
}



public function deleteBook()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;

        if ($id) {
            $bookModel = new Book();
            $bookModel->deleteBook($id);
            header('Location: /user/list-books');
            exit;
        } else {
            echo "Book ID is required to delete.";
        }
    } else {
        echo "Invalid request method.";
    }
}



public function requestBook()
{
    $user = $_SESSION['user'] ?? null;

    if ($user) {
        $bookModel = new Book();
        $userId = $user['id'];
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $isbn = $_POST['isbn'] ?? null;
        $bookModel->requestBook( $userId,$title, $author, $isbn);
        header('Location: /user/request-book?status=pending');
        exit;
    } else {
        header('Location: /login');
        exit;
    }
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
            header("Location: /user/dashboard");
            exit();
        }

        if (isset($_POST['request_id'], $_POST['status'])) {
            $requestId = $_POST['request_id'];
            $status = $_POST['status'];

            if($status=='approved'){
                $status= 'awaiting_super_admin';
            }

            $bookModel = new Book();
            $bookModel->updateRequestStatus($requestId, $status);
            header("Location: /librarian/view-requests");
            exit();
        }
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
       
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        
        $bookId = $_POST['book_id'] ?? null;
        if (!$bookId) {
            header('Location: /user/list-books');
            exit;
        }

        
        $bookModel = new Book();     
        $borrowedBook = $bookModel->isBookBorrowed($bookId);
        
        if ($borrowedBook) {
            
            header('Location: /user/list-books?error=already_borrowed');
            exit;
        }      
        $borrowDate = date('Y-m-d H:i:s');
        $returnDate = date('Y-m-d H:i:s', strtotime('+10 days'));
        $userId = $_SESSION['user']['id'];       
        $bookModel->borrowBook($userId, $bookId, $borrowDate, $returnDate);       
        $bookModel->updateBookStatus($bookId, 'borrowed');       
        header('Location: /user/list-books?success=book_borrowed');
        exit;
    }
    }

   
}






