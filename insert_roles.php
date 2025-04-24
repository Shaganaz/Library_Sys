<?php
require_once __DIR__ . '/vendor/autoload.php';
use Shaganaz\Libsys\Core\QueryBuilder;
$query = new QueryBuilder();


$roles = ['super_admin', 'librarian', 'teacher', 'student'];

foreach ($roles as $role) {
    $query->insert('roles', ['name' => $role]);
}

echo "Sample roles inserted successfully.\n";
