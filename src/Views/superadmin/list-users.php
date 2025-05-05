<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f4f4f4;
    }

    form {
        margin: 0;
    }

    select {
        padding: 5px;
    }

    button {
        margin-top: 5px;
        padding: 5px 10px;
    }
</style>

<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Current Role</th>
            <th>Update Role</th>
            <th>Updated Role</th> 
            <th>Delete User</th> 
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role_name']; ?></td>
                <td>
    <form class="update-role-form" method="POST" data-user-id="<?= $user['id']; ?>">
        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
        <select name="role_id">
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role['id']; ?>" <?= $role['id'] == $user['role_id'] ? 'selected' : ''; ?>>
                    <?= $role['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Update Role</button>
    </form>
</td>
<td class="role-feedback-<?= $user['id']; ?>">
    <?= isset($user['updated_role']) ? $user['updated_role'] : ''; ?>
</td>

<td>
    <form class="delete-user-form" data-user-id="<?= $user['id']; ?>">
        <button type="submit">Delete</button>
    </form>
</td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="/logout" class="btn">Logout</a>
<?php if (isset($message)): ?>
    <div class="alert alert-success">
        <?php echo $message; ?>
    </div>
<?php endif; ?>




<script>
document.querySelectorAll('.update-role-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const userId = form.dataset.userId;

        fetch('/superadmin/list-users', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'  
            }
        })
        .then(res => res.json())
        .then(data => {
            const feedbackCell = document.querySelector(`.role-feedback-${userId}`);
            if (data.success) {
                feedbackCell.innerHTML = `<span style="color:green;">${data.updated_role}</span>`;
            } else {
                feedbackCell.innerHTML = `<span style="color:red;">Error updating role</span>`;
            }
        })
        .catch(err => {
            console.error('AJAX error', err);
            alert('Something went wrong.');
        });
    });
});
</script>


<script>
document.querySelectorAll('.delete-user-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!confirm('Are you sure you want to delete this user?')) return;

        const userId = form.dataset.userId;

        fetch('/superadmin/delete-user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                user_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                form.closest('tr').remove();  
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the user.');
        });
    });
});
</script>
