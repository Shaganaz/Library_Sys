<form method="POST">
    <!-- Name input -->
    <input type="text" name="name" placeholder="Name" required>
    
    <!-- Email input -->
    <input type="email" name="email" placeholder="Email" required>

    <!-- Password input -->
    <input type="password" name="password" placeholder="Password" required>

    <!-- Role selection dropdown -->
    <select name="role_id" id="role_id" required>
        <option value="" disabled selected>-- Select a Role --</option>
        <?php foreach ($roles as $role): ?>
            <option value="<?= $role['id']; ?>"><?= ucfirst($role['name']); ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Submit button -->
    <button type="submit">Create User</button>
</form>
