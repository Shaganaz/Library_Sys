<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .error-message {color : red;}
        .success-message { color: green; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form id="loginForm">
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="submit-btn">Login</button>
    </form>
    <div id="loginMessage"></div>
   
<p class="register-link">New user? <a href="/register">Register here</a></p>
</div>

<script>
 document.getElementById('loginForm').addEventListener('submit', async function (e){
    e.preventDefault();

    const form = e.target;
    const formData= new FormData(form);
    const loginMessage = document.getElementById('loginMessage');

    try {
        const response = await fetch('/login', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            loginMessage.textContent = 'Login successful! Redirecting...';
            loginMessage.className = 'success-message';
            setTimeout(() => {
                window.location.href = result.redirect;
            }, 1000);
        } else {
            loginMessage.textContent = result.message;
            loginMessage.className = 'error-message';
        }
    } catch (error) {
        loginMessage.textContent = 'An error occurred. Please try again.';
        loginMessage.className = 'error-message';
        console.error(error);
    }

    
 });
</script>


</body>
</html>
