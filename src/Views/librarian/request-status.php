<h1>Update Book Request Status</h1>

<form id="update-status-form">
    <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['id']); ?>">
    <label for="status">Status:</label>
    <select name="status" id="status">
        <option value="approved" <?php echo ($request['status'] === 'approved') ? 'selected' : ''; ?>>Approve</option>
        <option value="rejected" <?php echo ($request['status'] === 'rejected') ? 'selected' : ''; ?>>Reject</option>
    </select>
    <button type="submit">Update Status</button>
</form>
<div id="status-message"></div>



<script>
document.getElementById('update-status-form').addEventListener('submit', function(event) {
    event.preventDefault(); 

    const form = event.target;
    const formData = new FormData(form);

    fetch('/librarian/request-status', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const msgDiv = document.getElementById('status-message');
        if (data.success) {
            msgDiv.innerHTML = `<p style="color: green;">${data.message}</p>`;
        } else {
            msgDiv.innerHTML = `<p style="color: red;">${data.message}</p>`;
        }
    })
    .catch(error => {
        document.getElementById('status-message').innerHTML = `<p style="color: red;">An error occurred. Please try again.</p>`;
        console.error('Error:', error);
    });
});
</script>
