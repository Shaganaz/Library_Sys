
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Book</title>
</head>
<body>

    <h1>Create a New Book</h1>

    <form id="createBookForm" method="POST">
        <label>ID:</label>
        <input type="number" name="id" required><br><br>

        <label>Title:</label>
        <input type="text" name="title" required><br><br>

        <label>Author:</label>
        <input type="text" name="author" required><br><br>

        <label>ISBN:</label>
        <input type="text" name="isbn" required><br><br>

        <button type="submit">Create Book</button>
        <a href="/logout" class="btn">Logout</a>
    </form>

    
    <script>
    document.getElementById('createBookForm').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const form = e.target;
    const formData = new FormData(form);

    fetch('/user/create-book', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Book created successfully!');
            window.location.href = '/user/list-books'; 
        } else {
            alert(data.message || 'Failed to create book.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong.');
    });
});
</script>

</body>
</html>
