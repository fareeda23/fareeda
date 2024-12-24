<?php
session_start();
require '../db.php';

try {
    $user_id = $_SESSION['user']['id'];
    $query_annc = "SELECT student.USN, announcement.* FROM student LEFT JOIN department ON student.dept_id = department.dept_id LEFT JOIN announcement ON department.dept_id = announcement.dept_id WHERE student.USN ='".$user_id."';";
    $user_details = $pdo->query($query_annc);
    $announcements = $user_details->fetchAll(PDO::FETCH_ASSOC);
    $query_annc_type = "SELECT * FROM announcement_type"; 
    $announcement_t = $pdo->query($query_annc_type);
    $announcements_type = $announcement_t->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            width: 85%;
            margin: 50px auto;
            max-width: 1200px;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }

        .announcement-list {
            list-style-type: none;
            padding: 0;
        }

        .announcement-item {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 10px 0;
            cursor: pointer;
        }

        .announcement-item:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .announcement-title {
            font-size: 1.8em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .announcement-date {
            font-size: 14px;
            color: #777;
        }

        /* Modal Styles */
        .modal-content {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../student_dashboard.php">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#courses">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="#profile">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h1 class="text-center">Announcements</h1>
        <p class="text-center">All the announcements will be listed here.</p>
        
        <!-- Manage Subscription Button -->
        <button class="btn btn-primary" id="manageSubscriptionBtn" data-bs-toggle="modal" data-bs-target="#subscriptionModal">
            Manage Subscription
        </button>

        <!-- Announcement List -->
        <ul class="announcement-list" id="announcement-list">
            <?php foreach ($announcements as $announcement): ?>
                <li class="announcement-item" data-id="<?= $announcement['Announce_ID'] ?>" data-title="<?= htmlspecialchars($announcement['Title']) ?>" data-content="<?= htmlspecialchars($announcement['Content']) ?>" data-posted-date="<?= $announcement['Posted_Date'] ?>">
                    <div class="announcement-title"><?= htmlspecialchars($announcement['Title']) ?></div>
                    <div class="announcement-date">Posted on: <?= $announcement['Posted_Date'] ?></div>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Announcement Modal -->
        <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="announcementModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-content"></div>
                </div>
            </div>
        </div>

        <!-- Subscription Modal -->
        <div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="subscriptionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="subscriptionModalLabel">Manage Your Subscription</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="subscriptionForm">
                            <p>Select the types of announcements you'd like to subscribe to:</p>
                            <!-- Add dynamic subscription types here -->
                            <?php
                            // Example subscription types (You can replace this with dynamic content)
                            foreach ($announcements_type as $type): ?>
                                <div>
                                    <input type="checkbox" class="form-check-input" id="subscription-<?= $type['id'] ?>" name="subscriptions[]" value="<?= $type['id'] ?>">
                                    <label class="form-check-label" for="subscription-<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></label>
                                </div>
                            <?php endforeach; ?>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Subscription</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        // Handle clicking on an announcement item to show the modal
        document.querySelectorAll('.announcement-item').forEach(item => {
            item.addEventListener('click', () => {
                const title = item.getAttribute('data-title');
                const content = item.getAttribute('data-content');
                document.getElementById('announcementModalLabel').textContent = title;
                document.getElementById('modal-content').textContent = content;

                const announcementModal = new bootstrap.Modal(document.getElementById('announcementModal'));
                announcementModal.show();
            });
        });
    </script>
</body>
</html>
