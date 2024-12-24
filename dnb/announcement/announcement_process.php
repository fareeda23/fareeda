<?php
session_start();
require '../db.php';

try {
    $dept_id = $_SESSION['user']['id'];

    if (isset($_POST['action_type'])) {
        $action_type = $_POST['action_type'];

        // Add Action
        if ($action_type == 'add') {
            // Collect POST data
            $title = $_POST['title'];
            $content = $_POST['content'];
            $announcement_type_id = $_POST['announcement_type_id'];

            // Prepare and execute the insert statement
            $insertStmt = $pdo->prepare("
                INSERT INTO announcement (Dept_ID, announcement_type_id, Title, Content, posted_date) 
                VALUES (:dept_id, :announcement_type_id, :title, :content, CURDATE())
            ");
            
            $insertStmt->execute([
                ':dept_id' => $dept_id,
                ':announcement_type_id' => $announcement_type_id,
                ':title' => $title,
                ':content' => $content
            ]);
            
            // Redirect after adding
            header("Location: manage_announcement.php");
            exit;
        }
        
        // Edit Action
        elseif ($action_type == 'edit') {
            // Collect POST data
            $announcement_id = $_POST['announce_id'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $announcement_type_id = $_POST['announcement_type_id'];

            // Prepare and execute the update statement
            $updateStmt = $pdo->prepare("
                UPDATE announcement 
                SET announcement_type_id = :announcement_type_id, 
                    Title = :title, 
                    Content = :content, 
                    posted_date = CURDATE() 
                WHERE announce_id = :announce_id AND Dept_ID = :dept_id
            ");
            
            $updateStmt->execute([
                ':announce_id' => $announcement_id,
                ':dept_id' => $dept_id,
                ':announcement_type_id' => $announcement_type_id,
                ':title' => $title,
                ':content' => $content
            ]);

            // Redirect after editing
            header("Location: manage_announcement.php");
            exit;
        }
        
        // Delete Action
        elseif ($action_type == 'delete') {
            // Collect POST data
            $announcement_id = $_POST['announce_id'];

            // Prepare and execute the delete statement
            $deleteStmt = $pdo->prepare("DELETE FROM announcement WHERE announce_id = :announce_id AND Dept_ID = :dept_id");
            $deleteStmt->execute([
                ':announce_id' => $announcement_id,
                ':dept_id' => $dept_id
            ]);

            // Redirect after deleting
            header("Location: manage_announcement.php");
            exit;
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
