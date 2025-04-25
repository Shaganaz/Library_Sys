<h1>Request a Book</h1>

<form action="/user/request-book" method="POST" style="max-width: 500px;">
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
<?php
$status = $_GET['status'] ?? null;
if ($status === 'pending') {
    echo '<p>Your book request has been submitted and is pending approval by the librarian.</p>';
} elseif ($status === 'approved_by_librarian') {
    echo '<p>Your book request has been approved by the librarian and is awaiting approval by the super admin.</p>';
} elseif ($status === 'approved_by_super_admin') {
    echo '<p>Your book request has been approved by both the librarian and the super admin.</p>';
} elseif ($status === 'rejected_by_librarian') {
    echo '<p>Your book request has been rejected by the librarian.</p>';
} elseif ($status === 'rejected_by_super_admin') {
    echo '<p>Your book request has been rejected by the super admin.</p>';
}
?>