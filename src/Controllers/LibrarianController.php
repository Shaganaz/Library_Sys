<?php
namespace Shaganaz\Libsys\Controllers;

use Shaganaz\Libsys\Core\View;
use Shaganaz\Libsys\Models\User;
use Shaganaz\Libsys\Core\Database;

class LibrarianController {
    public function viewPendingRequests() {
        $db = Database::getInstance();
        $query = "SELECT * FROM book_requests WHERE status = 'pending'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $pendingRequests = $stmt->fetchAll();
        View::render('librarian/pending-requests', ['pendingRequests' => $pendingRequests]);
    }
    public function approveRequest($requestId) {
        $db = Database::getInstance();
        $query = "UPDATE book_requests SET status = 'approved' WHERE id = :requestId";
        $stmt = $db->prepare($query);
        $stmt->execute([':requestId' => $requestId]);

        header('Location: /librarian/pending-requests');
        exit();
    }

    public function rejectRequest($requestId) {
        $db = Database::getInstance();
        $query = "UPDATE book_requests SET status = 'rejected' WHERE id = :requestId";
        $stmt = $db->prepare($query);
        $stmt->execute([':requestId' => $requestId]);

        header('Location: /librarian/pending-requests');
        exit();
    }

    public function createRequest($userId, $bookId) {
        $db = Database::getInstance();
        $query = "INSERT INTO book_requests (user_id, book_id, status) VALUES (:userId, :bookId, 'pending')";
        $stmt = $db->prepare($query);
        $stmt->execute([':userId' => $userId, ':bookId' => $bookId]);
        header("Location: /user/request-book"); 
        exit();
    }

}
