<?php

namespace Shaganaz\Libsys\Middleware;

class AuthMiddleware {
    public function handle() {
        

        // Check if the user is logged in by verifying the session
        if (!isset($_SESSION['user'])) {
            // Redirect to login page if user is not logged in
            header('Location: /login');
            exit;
        }
    }
}
