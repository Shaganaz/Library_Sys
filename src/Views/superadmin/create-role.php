<?php if (isset($_SESSION['success_message'])): ?>
    <div class="success-message">
        <?= $_SESSION['success_message']; ?>
        <?php unset($_SESSION['success_message']); ?>
    </div>
<?php endif; ?>
<h2>Create New Role</h2>
<form id="create-role-form">
    <label for="name">Role Name:</label>
    <input type="text" id="name" name="name" required>
    <button type="submit">Create Role</button>
    <a href="/logout" class="btn">Logout</a>
</form>


<div id="ajax-message" class="success-message" style="display:none;"></div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('create-role-form');
    const messageBox = document.getElementById('ajax-message');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const roleName = formData.get('name');

        fetch('/superadmin/create-role', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `name=${encodeURIComponent(roleName)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageBox.textContent = data.message;
                messageBox.style.display = 'block';
                form.reset();
            } else {
                messageBox.textContent = data.message || 'Failed to create role.';
                messageBox.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageBox.textContent = 'An error occurred.';
            messageBox.style.display = 'block';
        });
    });
});
</script>