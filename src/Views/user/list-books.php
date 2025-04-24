<style>
    .highlight {
        background-color: #ffeeba; /* light yellow */
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

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-top: 16px;">
    <thead style="background-color: #f2f2f2;">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>ISBN</th>
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
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No books available.</td>
        </tr>
    <?php endif; ?>
</tbody>
</table>
