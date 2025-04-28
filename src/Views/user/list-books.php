<style>
    .highlight {
        background-color: #FFEEBA; /* light yellow */
        font-weight: bold;
        animation: popRow 0.6s ease-in-out;
    }
    @keyframes popRow {
        0%   { transform: scale(1); }
        50%  { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
<div style="display: flex; justify-content: space-between; align-items: center;">
    <h1>List of Books</h1>
    <a href="/user/create-book" style="padding: 8px 12px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">+ Create New Book</a>
</div>
<div style="text-align: left; margin: 16px 0;">
    <form method="GET" action="/user/list-books" style="display: inline-block;">
        <input type="text" name="search" placeholder="Search by ID or Title"
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
               style="padding: 6px; width: 250px;">
        <button type="submit" style="padding: 6px 12px;">Search</button>
    </form>
</div>
<?php
$searchTerm = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
?>
<?php if (isset($_GET['success']) && $_GET['success'] == 'book_borrowed'): ?>
    <div style="padding: 10px; background-color: #D4EDDA; color: #155724; margin-bottom: 15px; border: 1px solid #C3E6CB;">
        Book borrowed successfully! Please return within 10 days.
    </div>
<?php endif; ?>
<?php if (isset($_GET['error']) && $_GET['error'] == 'already_borrowed'): ?>
    <div style="padding: 10px; background-color: #F8D7DA; color: #721C24; margin-bottom: 15px; border: 1px solid #F5C6CB;">
        This book is already borrowed by someone else.
    </div>
<?php endif; ?>
<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-top: 16px;">
    <thead style="background-color: #F2F2F2;">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>ISBN</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (isset($books) && count($books) > 0): ?>
        <?php foreach ($books as $book): ?>
            <?php
                $match = (
                    $searchTerm &&
                    (stripos($book['id'], $searchTerm) !== false || stripos($book['title'], $searchTerm) !== false)
                );
            ?>
            <tr class="<?php echo $match ? 'highlight' : ''; ?>">
                <td><?php echo htmlspecialchars($book['id']); ?></td>
                <td><?php echo htmlspecialchars($book['title']); ?></td>
                <td><?php echo htmlspecialchars($book['author']); ?></td>
                <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                <td>
                    <?php if (isset($_SESSION['user']['role_name']) &&
                              ($_SESSION['user']['role_name'] === 'librarian' || $_SESSION['user']['role_name'] === 'super_admin')): ?>
                        <a href="/user/edit-book?id=<?php echo urlencode($book['id']); ?>"
                           style="padding: 4px 8px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px; margin-right: 4px;">
                           Edit
                        </a>
                        <form action="/user/delete-book" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo urlencode($book['id']); ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this book?');"
                                    style="padding: 4px 8px; background-color: #DC3545; color: white; text-decoration: none; border-radius: 4px;">
                                Delete
                            </button>
                        </form>
                    <?php elseif ($borrowedBook = $bookModel->isBookBorrowed($book['id'])): ?>
                        <span>Already Borrowed</span>
                    <?php else: ?>
                        <form action="/user/borrow-book" method="POST">
                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                            <button type="submit" style="padding: 4px 8px; background-color: #28A745; color: white; text-decoration: none; border-radius: 4px;">
                                Borrow
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No books available.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<a href="/logout" class="btn" >Logout</a>
