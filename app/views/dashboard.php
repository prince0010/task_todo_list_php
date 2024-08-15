<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> </h2>
    <p><a href="/logout">Logout</a></p>
</body>
</html>
