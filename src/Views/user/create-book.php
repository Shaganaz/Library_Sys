
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Book</title>
</head>
<body>

    <h1>Create a New Book</h1>

    <form method="POST" action="/user/create-book">
        <label>ID:</label>
        <input type="number" name="id" required><br><br>

        <label>Title:</label>
        <input type="text" name="title" required><br><br>

        <label>Author:</label>
        <input type="text" name="author" required><br><br>

        <label>ISBN:</label>
        <input type="text" name="isbn" required><br><br>

        <button type="submit">Create Book</button>
    </form>

</body>
</html>
