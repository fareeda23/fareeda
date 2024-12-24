<?php
session_start();
require 'db.php';

$type = 'student'; // Default to student

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type']; // Get selected type (student or department)

    // Check if user is a Student
    if ($type == 'student') {
        $stmt = $pdo->prepare("SELECT * FROM Student WHERE USN = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['Password'])) {
            // Student Login
            $_SESSION['user'] = [
                'id' => $user['USN'],
                'name' => $user['Name'],
                'type' => 'student',
            ];
            header('Location: student_dashboard.php');
            exit;
        }
    }

    // Check if user is from Department
    if ($type == 'department') {
        $stmt = $pdo->prepare("SELECT * FROM Department WHERE Dept_ID = :username");
        $stmt->execute(['username' => $username]);
        $dept = $stmt->fetch();

        if ($dept && password_verify($password, $dept['Password'])) {
            // Department Login
            $_SESSION['user'] = [
                'id' => $dept['Dept_ID'],
                'name' => $dept['Dept_Name'],
                'type' => 'department',
            ];
            header('Location: department_dashboard.php');
            exit;
        }
    }

    // Invalid Credentials
    $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> <!-- Add this for Bootstrap Icons -->
    <style>
        body {
            background: url('https://your-image-url.com') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 450px;
            margin: 100px auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        .alert {
            margin-top: 20px;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            background-color: #1a73e8;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .custom-control-label {
            font-size: 16px;
        }

        .form-check-label {
            font-size: 18px;
        }

        .form-check-inline {
            margin-right: 10px;
        }

        .tooltip-inner {
            background-color: #1a73e8;
            color: #fff;
        }

        .eye-icon {
            position: absolute;
            top: 60%; /* Adjust this value for better alignment */
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* Change the eye icon color to black */
        .eye-icon i {
            color: black;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">USN / Department ID</label>
                <input type="text" class="form-control" id="username" name="username" required 
                       placeholder="Enter USN or Department ID"
                       data-bs-toggle="tooltip" data-bs-placement="top" title="Please enter your USN or Department ID">
            </div>
            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required 
                       placeholder="Enter Password"
                       data-bs-toggle="tooltip" data-bs-placement="top" title="Please enter your password">
                <span id="togglePassword" class="eye-icon">
                    <i class="bi bi-eye-slash"></i>
                </span>
            </div>

            <!-- Role Selection with Tooltip and Hover Effects -->
            <div class="mb-3">
                <label class="form-check-label">Select Role</label><br>
                <div class="form-check form-check-inline">
                    <input type="radio" id="student" name="type" value="student" checked class="form-check-input"
                           data-bs-toggle="tooltip" data-bs-placement="top" title="Choose this if you're a Student">
                    <label for="student" class="form-check-label">Student</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="department" name="type" value="department" class="form-check-input"
                           data-bs-toggle="tooltip" data-bs-placement="top" title="Choose this if you're from a Department">
                    <label for="department" class="form-check-label">Department</label>
                </div>
            </div>

            <!-- Remember Me Toggle -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember Me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>

            <!-- Forgot Password Link -->
            <div class="text-center mt-3">
                <a href="#" class="text-decoration-none text-white" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot your password?</a>
            </div>
        </form>

        <div class="text-center mt-4">
            <p>Don't have an account? <a href="register.php" class="text-white">Sign up here</a></p>
        </div>
    </div>

    <!-- Modal for Forgot Password -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="email" class="form-label">Enter your email address</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, Popper, and Tooltips -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Show/Hide Password Functionality with Eye Icon
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const passwordFieldType = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', passwordFieldType);

            // Toggle Eye Icon
            this.innerHTML = passwordFieldType === 'password' ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
        });
    </script>
</body>
</html>
