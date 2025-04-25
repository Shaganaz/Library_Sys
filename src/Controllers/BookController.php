<?php
namespace Shaganaz\Libsys\Controllers;

use Shaganaz\Libsys\Core\View;
use Shaganaz\Libsys\Models\Book;

class BookController
{
    public function listBooks()
{
    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $query = trim($_GET['search']);
        $bookModel = new Book();
        $books = $bookModel->searchBooks($query);
    } else {
        $bookModel = new Book();
        $books = $bookModel->getAllBooks(); 

    }
    View::render('user/list-books', ['books' => $books]);
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
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $isbn = $_POST['isbn'] ?? null;
        $bookRequest = new Book();
        $bookRequest->requestBook($user['id'], $title, $author, $isbn);
        header('Location: /user/request-book?status=pending');
        exit;
    } else {
        header('Location: /login');
        exit;
    }
}


public function approveByLibrarian()
{
    $requestId = $_POST['request_id'] ?? null;
    
    if ($requestId) {

        $this->book->approveByLibrarian($requestId);
        header('Location: /librarian/pending-requests'); 
    } else {
        header('Location: /librarian/pending-requests'); 
    }
}


public function rejectByLibrarian()
{
    $requestId = $_POST['request_id'] ?? null;

    if ($requestId) {
       
        $this->book->rejectByLibrarian($requestId);
        header('Location: /librarian/pending-requests');
    } else {
        header('Location: /librarian/pending-requests');
    }
}



}


