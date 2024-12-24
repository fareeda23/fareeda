<?php
session_start();
require '../db.php';
try {
    $dept_id = $_SESSION['user']['id'];
    // Query to fetch data
    $stmt_ann = $pdo->query("SELECT Announce_ID,announcement_type_id, Title, Content FROM announcement WHERE Dept_ID =".$dept_id.";");
    $stmt_anntype = $pdo->query("SELECT id, name FROM announcement_type ;");
    // Fetch data as associative array
    $announcement_types = $stmt_anntype->fetchAll(PDO::FETCH_ASSOC);
    $announcements = $stmt_ann->fetchAll(PDO::FETCH_ASSOC);
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
        .container {
            padding-top: 50px;
        }
        h1 {
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Department Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_announcement.php">Add Announcement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_announcements.php">View Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h1 class="text-center">Manage Announcements</h1>
        <p class="text-center">Manage announcements and departmental tasks efficiently.</p>

        <!-- Cards Section -->
        <div class="row mt-5">
              <div class="container mt-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>PHP CRUD Table</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add Item</button>
                    </div>

                    <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sl_no = 1;
                        foreach($announcements as $announcement){ ?>
                        <tr data-announcement-id="<?php echo $announcement['Announce_ID']; ?>" data-announcement-type-id="<?php echo $announcement['announcement_type_id']; ?>">
                        <td><?php echo $sl_no++; ?></td>
                        <td><?php echo $announcement['Title']; ?></td>
                        <td><?php echo $announcement['Content'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                        </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                </div>

                <!-- Add Modal -->
                <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form method="POST" action="announcement_process.php">
                            <input type="hidden" class="form-control" name="action_type" value="add">
                            <div class="mb-3">
                                <label for="announcement_type_id" class="form-label">Announcement Type</label>
                                <select name="announcement_type_id" id="announcement_type_id_add" class="form-select" required>
                                    <option value="">Select Announcement Type</option>
                                    <?php foreach($announcement_types as $announcement_type){ ?>
                                    <option value="<?php echo $announcement_type['id']; ?>"><?php echo $announcement_type['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                            <label for="addName" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" id="title">
                            </div>
                            <div class="mb-3">
                            <label for="addEmail" class="form-label">Content</label>
                            <textarea type="text" name="content" class="form-control" id="content"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form method="POST" action="announcement_process.php">
                            <input type="hidden" class="form-control" name="action_type" value="edit">
                            <input type="hidden" name="announce_id" id="announceId" >
                            <div class="mb-3">
                                <label for="announcement_type_id" class="form-label">Announcement Type</label>
                                <select name="announcement_type_id" id="announcement_type_id" class="form-select" required>
                                    <option value="">Select Announcement Type</option>
                                    <?php foreach($announcement_types as $announcement_type) { ?>
                                        <option value="<?php echo $announcement_type['id']; ?>">
                                            <?php echo $announcement_type['name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editTitle" name="title">
                            </div>
                            <div class="mb-3">
                            <label for="editContent" class="form-label">Content</label>
                            <textarea type="email" class="form-control" id="editContent" name="content"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="POST" action="announcement_process.php">
                            <input type="hidden" class="form-control" name="action_type" value="delete">
                            <input type="hidden" name="announce_id" id="announceIdDel" >
                    Are you sure you want to delete this item?
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const row = button.closest('tr'); // Get the closest row

            // Extract data from the row
            const name = row.cells[1].textContent;
            const email = row.cells[2].textContent;
            const announcementId = row.getAttribute('data-announcement-id');
            const announcementTypeId = row.getAttribute('data-announcement-type-id'); // Get announcement_type_id

            // Populate the modal inputs
            document.getElementById('editTitle').value = name;
            document.getElementById('editContent').value = email;
            document.getElementById('announceId').value = announcementId;
            // Set the selected option in the dropdown
            const selectElement = document.getElementById('announcement_type_id');
            selectElement.value = announcementTypeId; // Set the selected announcement type
        });
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Get the delete modal element
            const deleteModal = document.getElementById('deleteModal');

            // Add event listener for when the modal is shown
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Button that triggered the modal
                const row = button.closest('tr'); // Get the closest row

                // Extract the announcement ID from the row's data-announcement-id attribute
                const announcementId = row.getAttribute('data-announcement-id');

                // Set the announcement ID in the delete confirmation form
                document.getElementById('announceIdDel').value = announcementId;
            });
        });


  </script>
</body>
</html>
