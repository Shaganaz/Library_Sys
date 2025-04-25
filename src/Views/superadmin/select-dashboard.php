<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 400px;
    margin: 100px auto;
    padding: 20px;
    background-color: #ffffff;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 8px;
}

h1 {
    margin-bottom: 10px;
    font-size: 24px;
    color: #333;
}

p {
    margin-bottom: 20px;
    color: #555;
}

.dashboard-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn {
    display: block;
    padding: 12px;
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    transition: background-color 0.2s ease;
}

.btn:hover {
    background-color: #0056b3;
}

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, Super Admin!</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Welcome, Super Admin!</h1>
        <p>Choose which dashboard you want to access:</p>
        <div class="dashboard-buttons">
            <a href="/superadmin/dashboard" class="btn btn-superadmin">Super Admin Dashboard</a>
            <a href="/user/dashboard" class="btn btn-user">User Dashboard</a>
        </div>
    </div>
</body>
</html>
