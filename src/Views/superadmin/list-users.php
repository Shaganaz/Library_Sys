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
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role_name']; ?></td>
                <td>
                    <form action="/superadmin/list-users" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="role_id">
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['id']; ?>" <?php echo $role['id'] == $user['role_id'] ? 'selected' : ''; ?>>
                                    <?php echo $role['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Update Role</button>
                    </form>
                </td>
                <td>
                    <?php echo isset($user['updated_role']) ? $user['updated_role'] : ''; ?>
                </td> 
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php if (isset($message)): ?>
    <div class="alert alert-success">
        <?php echo $message; ?>
    </div>
<?php endif; ?>