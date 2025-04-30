<h2>Your Borrowed Books</h2>
<?php if (!empty($borrowedBooks)): ?>
    <ul>
        <?php foreach ($borrowedBooks as $book): ?>
            <li><?php echo htmlspecialchars($book['book_id']); ?> - Borrowed on: <?php echo $book['borrowed_date']; ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No borrowed books yet.</p>
<?php endif; ?>
