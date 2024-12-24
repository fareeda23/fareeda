<?php
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dept_id = $_POST['dept_id'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM Department WHERE Dept_ID = :dept_id");
    $stmt->execute(['dept_id' => $dept_id]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user'] = [
            'id' => $user['Dept_ID'],
            'name' => $user['Dept_Name'],
            'type' => 'department',
        ];
        header('Location: ../announcements/add_announcement.php');
        exit;
    } else {
        $error = "Invalid Department ID or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Department Login</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="dept_id" class="form-label">Department ID</label>
                <input type="text"