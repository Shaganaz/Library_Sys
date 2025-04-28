<h1>Are you sure you want to delete this user?</h1>

<p>Name: <?php echo htmlspecialchars($user['name']); ?></p>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

<form method="POST" action="/superadmin/delete-user">
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>"> 
    <button type="submit">Yes, Delete User</button>
</form>

<a href="/superadmin/dashboard">Cancel</a> 