<form method="POST">

    <input type="text" name="name" placeholder="Name" required>
    

    <input type="email" name="email" placeholder="Email" required>


    <input type="password" name="password" placeholder="Password" required>


    <select name="role_id" id="role_id" required>
        <option value="" disabled selected>-- Select a Role --</option>
        <?php foreach ($roles as $role): ?>
            <option value="<?= $role['id']; ?>"><?= ucfirst($role['name']); ?></option>
        <?php endforeach; ?>
    </select>


    <button type="submit">Create User</button>
    <a href="/logout" class="btn">Logout</a>
</form>
