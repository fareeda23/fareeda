<?php
session_start();
require '../db.php';

try {
    $user_id = $_SESSION['user']['id'];

    // Fetch all available announcement types
    $query_annc_type = "SELECT * FROM announcement_type";
    $announcement_t = $pdo->query($query_annc_type);
    $announcement_types = $announcement_t->fetchAll(PDO::FETCH_ASSOC);

    // Fetch user's current subscriptions
    $query_user_subs = "SELECT Announcement_Type_ID FROM Subscription WHERE USN = ?";
    $stmt = $pdo->prepare($query_user_subs);
    $stmt->execute([$user_id]);
    $user_subscriptions = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $subscriptions = $_POST['subscriptions'] ?? [];

        // Clear user's previous subscriptions
        $delete_query = "DELETE FROM Subscription WHERE USN = ?";
        $stmt = $pdo->prepare($delete_query);
        $stmt->execute([$user_id]);

        // Insert new subscriptions
        $insert_query = "INSERT INTO Subscription (USN, Announcement_Type_ID) VALUES (?, ?)";
        $stmt = $pdo->prepare($insert_query);
        foreach ($subscriptions as $type_id) {
            $stmt->execute([$user_id, $type_id]);
        }

        echo "Subscriptions updated successfully.";
        header("Location: manage_subscriptions.php");
    } catch (PDOException $e) {
        die("Error updating subscriptions: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Subscriptions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Subscriptions</h1>
        <form method="POST">
            <?php foreach ($announcement_types as $type): ?>
                <div>
                    <input type="checkbox" name="subscriptions[]" value="<?= $type['id'] ?>" 
                        <?= in_array($type['id'], $user_subscriptions) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($type['name']) ?>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary mt-3">Update Subscriptions</button>
        </form>
    </div>
</body>
</html>
