<h2>Edit Book</h2>
<form id="editBookForm" method="POST">
    <input type="hidden" name="id" id="bookId" value="<?php print_r($book['id'])?>">
    <label>Title:</label>
    <input type="text" name="title" id="title" value="<?php print_r($book['title'])?>"><br>
    <label>Author:</label>
    <input type="text" name="author" id="author" value="<?php print_r($book['author'])?>"><br>
    <label>ISBN:</label>
    <input type="text" name="isbn" id="isbn" value="<?php print_r($book['isbn'])?>"><br>
    <button type="submit">Update Book</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const bookId = urlParams.get('id');

    document.getElementById('editBookForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/user/edit-book', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Book Updated Successfully!');
                window.location.href = '/user/list-books';
            } else {
                alert(data.message || 'Failed to update book');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Something went wrong while updating');
        });
    });
});
</script>
