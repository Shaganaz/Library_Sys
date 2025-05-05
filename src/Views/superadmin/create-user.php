<form id="createUserForm" method="POST">
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

<div id="userFormMessage"></div>


<script>
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const form = e.target;
    const formData = new FormData(form);

    fetch('/superadmin/create-user', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const messageBox = document.getElementById('userFormMessage');
        if (data.success) {
            messageBox.innerHTML = `<div style="color: green;">${data.message}</div>`;
            form.reset(); 
        } else {
            messageBox.innerHTML = `<div style="color: red;">${data.message}</div>`;
        }
    })
    .catch(err => {
        console.error('Request failed:', err);
        document.getElementById('userFormMessage').innerHTML = `<div style="color: red;">An error occurred.</div>`;
    });
});
</script>
