<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['usn'])) {
    header("Location: examp.php");
    exit();
}

$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($name) ?>!</h1>
    <a href="logout.php">Logout</a>
</body>
</html>
