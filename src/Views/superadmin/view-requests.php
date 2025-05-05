<h1 style="text-align: center; margin-bottom: 20px;">Book Requests</h1>

<?php if (isset($requests) && !empty($requests)): ?>
    <table style="width: 90%; margin: 0 auto; border-collapse: collapse; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background-color: #f4f4f4;">
                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">User ID</th>
                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Title</th>
                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Author</th>
                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">ISBN</th>
                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Status</th>
                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($request['user_id']); ?></td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($request['title']); ?></td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($request['author']); ?></td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($request['isbn']); ?></td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($request['status']); ?></td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; gap: 8px;">
    <button class="status-btn" data-id="<?= $request['id']; ?>" data-status="approved" style="padding: 6px 10px; background-color: #4CAF50; color: white;">Approve</button>
    <button class="status-btn" data-id="<?= $request['id']; ?>" data-status="rejected" style="padding: 6px 10px; background-color: #F44336; color: white;">Reject</button>
</div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/logout" class="btn">Logout</a>
<?php else: ?>
    <p style="text-align: center;">No pending requests.</p>
<?php endif; ?>



<script>
document.querySelectorAll('.status-btn').forEach(button => {
    button.addEventListener('click', function () {
        const requestId = this.getAttribute('data-id');
        const status = this.getAttribute('data-status');

        fetch('/superadmin/request-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `request_id=${requestId}&status=${status}`
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed');
            return response.text(); 
        })
        .then(() => {
            this.closest('tr').remove();
        })
        .catch(error => {
            alert("Error updating request: " + error.message);
        });
    });
});
</script>