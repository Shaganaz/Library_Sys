<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
<div class="register-container">
    <h2>Register</h2>
    <form id="registerForm" action="/register" method="POST">
    <div class="input-group">
            <input type="text" name="name" placeholder="Full name" required>
        </div>
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Register</button>
    </form>
    <p id="registerMessage"></p>
    <p class="login-link">Already registered? <a href="/login">Login here</a></p>
</div>
<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const message = document.getElementById('registerMessage');

    try {
        const response = await fetch('/register', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            message.textContent = 'Registration successful! Redirecting...';
            message.className = 'success-message';
            setTimeout(() => {
                window.location.href = result.redirect;
            }, 1000);
        } else {
            message.textContent = result.message;
            message.className = 'error-message';
        }
    } catch (error) {
        message.textContent = 'Something went wrong. Please try again.';
        message.className = 'error-message';
        console.error(error);
    }
});
</script>
</body>
</html>