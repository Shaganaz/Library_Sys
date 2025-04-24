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
        $books = Book::searchBooks($query);
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
            Book::addBook([
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
}


