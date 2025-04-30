<form id="delete-form" method="POST" action="/user/delete-book">
    <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
    <button type="button" onclick="confirmDelete()">Delete</button>
</form>


<script>
function confirmDelete() {
    if (confirm("Are you sure you want to delete this book?")) {
        document.getElementById("delete-form").submit(); 
    }
}
</script>
