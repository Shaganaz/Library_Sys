<?php
namespace Shaganaz\Libsys\Models;

use Shaganaz\Libsys\Core\Database;
use PDO;

class Book
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAllBooks()
    {
        $stmt = $this->db->prepare("SELECT * FROM books");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  
    }

    public function searchBooks($query)
    {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id LIKE :query OR title LIKE :query");
        $stmt->execute([':query' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addBook($data)
    {
        $stmt = $this->db->prepare("INSERT INTO books (id, title, author, isbn) VALUES (:id, :title, :author, :isbn)");
        $stmt->execute([
            ':id' => $data['id'],
            ':title' => $data['title'],
            ':author' => $data['author'],
            ':isbn' => $data['isbn']
        ]);
    }
    public function getBookById($id)
{
    $stmt = $this->db->prepare("SELECT * FROM books WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateBook($data)
{
    $stmt = $this->db->prepare("UPDATE books SET title = :title, author = :author, isbn = :isbn WHERE id = :id");
    $stmt->execute([
        ':id' => $data['id'],
        ':title' => $data['title'],
        ':author' => $data['author'],
        ':isbn' => $data['isbn']
    ]);
}
public function deleteBook($bookId)
{
    // Start a transaction to ensure that both delete operations are executed successfully
    $this->db->beginTransaction();

    try {
        // First, remove all records from borrowed_books that reference this book
        $query = "DELETE FROM borrowed_books WHERE book_id = :book_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':book_id', $bookId);
        $stmt->execute();

        // Now delete the book from the books table
        $query = "DELETE FROM books WHERE id = :book_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':book_id', $bookId);
        $stmt->execute();

        // Commit the transaction if both queries succeeded
        $this->db->commit();

        return $stmt->rowCount() > 0;
    } catch (\Exception $e) {
        // If any exception occurs, rollback the transaction
        $this->db->rollBack();
        throw $e; // Re-throw the exception to handle it higher up (optional)
    }
}


public function requestBook($userId,$title, $author, $isbn = null)
{
    $userId = $_SESSION['user']['id']; 
    $stmt = $this->db->prepare("INSERT INTO book_requests (user_id,title, author, isbn , status) VALUES (:user_id,:title, :author, :isbn, :status)");
    return $stmt->execute([
        ':user_id' => $userId,
        ':title'   => $title,
        ':author'  => $author,
        ':isbn'    => $isbn,
        ':status' => 'pending'
    ]);
}

public function getPendingRequests()
{
    $stmt = $this->db->prepare("SELECT * FROM book_requests WHERE status = 'pending'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateRequestStatus($requestId, $status)
{
    $stmt = $this->db->prepare("UPDATE book_requests SET status = :status WHERE id = :id");
    $stmt->execute([
        ':status' => $status,
        ':id' => $requestId
    ]);
}

public function getAwaitingSuperAdminRequests()
{
    $sql = "SELECT * FROM book_requests WHERE status = 'awaiting_super_admin'";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function approveRequest($requestId)
{
    $sql = "UPDATE book_requests SET status = 'approved' WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $requestId, PDO::PARAM_INT);
    return $stmt->execute();
}

public function rejectRequest($requestId)
    {
        $sql = "UPDATE book_requests SET status = 'rejected' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $requestId, PDO::PARAM_INT);
        return $stmt->execute();
    }



    public function borrowBook($userId, $bookId, $borrowDate, $returnDate)
    {
        $query = "INSERT INTO borrowed_books (user_id, book_id, borrow_date, return_date, status)
                  VALUES (?, ?, ?, ?, 'borrowed')";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $bookId, $borrowDate, $returnDate]);
    }



    public function isBookBorrowed($bookId) {
        $query = "SELECT 1 FROM borrowed_books 
                  WHERE book_id = :book_id AND status = 'borrowed' LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':book_id', $bookId);
        $stmt->execute();
        return $stmt->fetchColumn() !== false;
    }
    

    public function returnBook($bookId, $userId) {
        $query = "UPDATE borrowed_books 
                  SET return_date = NOW(), status = 'returned' 
                  WHERE user_id = :user_id AND book_id = :book_id AND status = 'borrowed'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':book_id', $bookId);
        return $stmt->execute();
    }

    public function updateBookStatus($bookId, $status)
    {
        $query = "UPDATE books SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$status, $bookId]);
    }
    
}


