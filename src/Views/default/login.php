<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form action="/login" method="POST">
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="submit-btn">Login</button>
    </form>

    <?php
    if (isset($_GET['error'])) {
        if ($_GET['error'] === 'empty_fields') {
            echo '<p class="error-message">Please fill in both fields.</p>';
        } elseif ($_GET['error'] === 'invalid_credentials') {
            echo '<p class="error-message">Invalid email or password.</p>';
        }
    }
    ?>

<p class="register-link">New user? <a href="/register">Register here</a></p>

</div>

</body>
</html>
