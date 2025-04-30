<h1>Request a Book</h1>

<form id="requestBookForm" method="POST" style="max-width: 500px;">
    <div style="margin-bottom: 12px;">
        <label>Title:</label><br>
        <input type="text" name="title" required style="width: 100%; padding: 8px;">
    </div>

    <div style="margin-bottom: 12px;">
        <label>Author:</label><br>
        <input type="text" name="author" required style="width: 100%; padding: 8px;">
    </div>

    <div style="margin-bottom: 12px;">
        <label>ISBN (optional):</label><br>
        <input type="text" name="isbn" style="width: 100%; padding: 8px;">
    </div>

    <div style="margin-bottom: 12px;">
        <label>Reason for Request (optional):</label><br>
        <textarea name="reason" rows="4" style="width: 100%; padding: 8px;"></textarea>
    </div>

    <button type="submit" style="padding: 10px 16px; background-color: #007bff; color: white; border: none; border-radius: 4px;">Submit Request</button>    
</form>
<p id="statusMessage" style="margin-top: 16px; color: green;"></p>
<a href="/logout" class="btn">Logout</a>


<script>
document.getElementById('requestBookForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/user/request-book', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const statusMsg = document.getElementById('statusMessage');
        if (data.success) {
            statusMsg.textContent = 'Your book request has been submitted and is pending approval.';
            statusMsg.style.color = 'green';
            this.reset(); 
            this.querySelector('button[type="submit"]').disabled = true;
        } else {
            statusMsg.textContent = data.message || 'Something went wrong!';
            statusMsg.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const statusMsg = document.getElementById('statusMessage');
        statusMsg.textContent = 'An error occurred while submitting the request.';
        statusMsg.style.color = 'red';
    });
});
</script>