<?php
$user = $_SESSION['user'] ?? null;

?>

<h1>Welcome to Your Dashboard!</h1>
<p>Choose an option below:</p>

<ul>
    <li><a href="/user/list-books">List Books</a></li>
    <li><a href="/user/create-book">Create New Book</a></li>
    <li><a href="/user/request-book">Request a Book</a></li>
    <a href="/logout" class="btn">Logout</a>
</ul>

<?php if ($user && $user['role_name'] === 'librarian'): ?>
    <form action="/librarian/view-requests" method="GET">
        <button type="submit">View Book Requests</button>
    </form>
<?php endif; ?>

<?php if ($user && $user['role_name'] === 'librarian'): ?>
<?php endif; ?>
