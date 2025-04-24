<?php if (isset($_SESSION['success_message'])): ?>
    <div class="success-message">
        <?= $_SESSION['success_message']; ?>
        <?php unset($_SESSION['success_message']); ?>
    </div>
<?php endif; ?>
<h2>Create New Role</h2>
<form method="POST" action="/superadmin/create-role">
    <label for="name">Role Name:</label>
    <input type="text" id="name" name="name" required>
    <button type="submit">Create Role</button>
</form>
