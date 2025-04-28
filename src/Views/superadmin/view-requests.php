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
                        <form action="/superadmin/request-status" method="POST" style="display: flex; gap: 8px;">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" name="status" value="approved" style="padding: 6px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">Approve</button>
                            <button type="submit" name="status" value="rejected" style="padding: 6px 10px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer;">Reject</button>
                        </form>
                        <a href="/logout" class="btn">Logout</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align: center;">No pending requests.</p>
<?php endif; ?>
