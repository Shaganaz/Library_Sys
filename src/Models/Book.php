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
public function deleteBook($id)
{
    $stmt = $this->db->prepare("DELETE FROM books WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

public function requestBook($userId, $title, $author, $isbn = null)
{
    $stmt = $this->db->prepare("INSERT INTO book_requests (user_id, title, author, isbn , status) VALUES (:user_id, :title, :author, :isbn, :status)");
    return $stmt->execute([
        ':user_id' => $userId,
        ':title'   => $title,
        ':author'  => $author,
        ':isbn'    => $isbn,
        ':status' => 'pending'
    ]);
}

public function getPendingRequestsForSuperAdmin()
{
    $stmt = $this->db->prepare("SELECT * FROM book_requests WHERE status = 'approved_by_librarian'");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

public function getAllBookRequests()
{
    $stmt = $this->db->prepare("
        SELECT br.*, u.name AS user_name
        FROM book_requests br
        JOIN users u ON br.user_id = u.id
    ");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}


public function approveByLibrarian($requestId)
{
    $stmt = $this->db->prepare("UPDATE book_requests SET status = 'approved_by_librarian' WHERE id = :id");
    return $stmt->execute([':id' => $requestId]);
}

public function rejectByLibrarian($requestId)
{
    $stmt = $this->db->prepare("UPDATE book_requests SET status = 'rejected_by_librarian' WHERE id = :id");
    return $stmt->execute([':id' => $requestId]);
}

public function approveBySuperAdmin($requestId)
{
    $stmt = $this->db->prepare("UPDATE book_requests SET status = 'approved_by_super_admin' WHERE id = :id");
    return $stmt->execute([':id' => $requestId]);
}

public function rejectBySuperAdmin($requestId)
{
    $stmt = $this->db->prepare("UPDATE book_requests SET status = 'rejected_by_super_admin' WHERE id = :id");
    return $stmt->execute([':id' => $requestId]);
}


}
