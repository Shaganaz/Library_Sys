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
}
