<form method="POST" action="/user/edit-book">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($book['id']); ?>">
    <label>Title:</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>"><br>
    <label>Author:</label>
    <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>"><br>
    <label>ISBN:</label>
    <input type="text" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>"><br>
    <button type="submit">Update Book</button>
</form>
