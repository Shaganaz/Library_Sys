<h1>Update Book Request Status</h1>

<form action="/superadmin/request-status" method="POST">
    <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['id']); ?>">
    <label for="status">Status:</label>
    <select name="status" id="status">
        <option value="approved" <?php echo ($request['status'] === 'approved') ? 'selected' : ''; ?>>Approve</option>
        <option value="rejected" <?php echo ($request['status'] === 'rejected') ? 'selected' : ''; ?>>Reject</option>
    </select>
    <button type="submit">Update Status</button>
</form>
