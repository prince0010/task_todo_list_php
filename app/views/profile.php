<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <h2>Profile</h2>

    <p>Welcome, <?php echo $_SESSION['username']; ?></p>

    <?php if (isset($this->error)) echo "<p>{$this->error}</p>"; ?>
    
    <form action="/updateProfile" method="post">
        <label>New Username:</label>
        <input type="text" name="username" required><br>
        <label>New Password:</label>
        <input type="password" name="password" required><br>
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required><br>
        <button type="submit">Update Profile</button>
    </form>
    <p><a href="/logout">Logout</a></p>
</body>
</html>
