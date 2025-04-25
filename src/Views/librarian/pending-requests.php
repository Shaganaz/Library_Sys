<?php
// Assuming $bookRequests is an array of book requests fetched from the database
?>

<h1>Pending Book Requests</h1>

<?php if (!empty($bookRequests)): ?>
    <table>
        <thead>
            <tr>
                <th>Book Title</th>
                <th>User</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookRequests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['book_title']); ?></td>
                    <td><?php echo htmlspecialchars($request['user_name']); ?></td>
                    <td>
                        <form action="/librarian/approve" method="POST">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit">Approve</button>
                        </form>
                        <form action="/librarian/reject" method="POST">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No pending book requests.</p>
<?php endif; ?>
