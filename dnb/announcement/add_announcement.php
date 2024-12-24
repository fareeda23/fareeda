<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['type'] != 'department') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $dept_id = $_SESSION['user']['id'];

    $stmt = $pdo->prepare("INSERT INTO Announcement (Title, Content, Posted_Date, Dept_ID) VALUES (:title, :content, NOW(), :dept_id)");
    $stmt->execute([
        'title' => $title,
        'content' => $content,
        'dept_id' => $dept_id,
    ]);

    $success = "Announcement added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Announcement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Add Announcement</h2>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Announcement</button>
        </form>
    </div>
</body>
</html>