<?php
require_once __DIR__ . '/vendor/autoload.php';
use Shaganaz\Libsys\Core\QueryBuilder;
$query = new QueryBuilder();

$query->createTable('roles', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'name' => 'VARCHAR(50) NOT NULL UNIQUE'
]);
$query->createTable('users', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'name' => 'VARCHAR(100) NOT NULL',
    'email' => 'VARCHAR(100) NOT NULL UNIQUE',
    'password' => 'VARCHAR(255) NOT NULL',
    'role_id' => 'INT',
    'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'foreign' => 'FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL'
]);

echo "Tables created successfully.\n";
