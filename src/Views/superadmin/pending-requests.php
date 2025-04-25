<h2>Pending Book Requests</h2>

<?php if (empty($pendingRequests)) : ?>
    <p>No pending requests.</p>
<?php else : ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>User</th><th>Title</th><th>Author</th><th>ISBN</th><th>Reason</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pendingRequests as $req): ?>
                <tr>
                    <td><?= htmlspecialchars($req['user_name']) ?></td>
                    <td><?= htmlspecialchars($req['title']) ?></td>
                    <td><?= htmlspecialchars($req['author']) ?></td>
                    <td><?= htmlspecialchars($req['isbn']) ?></td>
                    <td><?= htmlspecialchars($req['reason']) ?></td>
                    <td>
                        <form method="POST" action="/superadmin/approve" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                            <button type="submit" name="action" value="approve">Approve</button>
                        </form>
                        <form method="POST" action="/superadmin/reject" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                            <button type="submit" name="action" value="reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
