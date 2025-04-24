<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
<div class="register-container">
    <h2>Register</h2>
    <form action="/?page=register" method="POST">
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
    <?php
    if (isset($_GET['error'])) {
        if ($_GET['error'] === 'email_exists') {
            echo '<p>Email is already registered.</p>';
        } elseif ($_GET['error'] === 'empty_fields') {
            echo '<p>Please fill out all fields.</p>';
        }
    }
    ?>
    <p class="login-link">Already registered? <a href="/login">Login here</a></p>
</div>
</body>
</html>