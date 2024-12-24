<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Digital Notice Board</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your existing CSS goes here */
    </style>
</head>
<body>
<div class="login-container">
    <div class="brand-logo">
        <img src="logo.png" alt="Logo">
        <h3>Sahyadri Digital Campus</h3>
    </div>

    <div class="tabs-container">
        <button id="facultyTab" class="active">Faculty</button>
        <button id="studentTab">Student</button>
    </div>

    <h2 id="loginTypeTitle">Faculty Login</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>
        <div class="show-password">
            <input type="checkbox" id="showPassword"> <label for="showPassword">Show Password</label>
        </div>
        <input type="hidden" name="loginType" id="loginType" value="faculty">
        <button type="submit" class="btn-custom">Login</button>
    </form>
</div>

<script>
    document.getElementById('showPassword').addEventListener('change', function() {
        const passwordField = document.getElementById('password');
        passwordField.type = this.checked ? 'text' : 'password';
    });

    document.getElementById('facultyTab').addEventListener('click', function() {
        document.getElementById('facultyTab').classList.add('active');
        document.getElementById('studentTab').classList.remove('active');
        document.getElementById('loginTypeTitle').textContent = 'Faculty Login';
        document.getElementById('loginType').value = 'faculty';
    });

    document.getElementById('studentTab').addEventListener('click', function() {
        document.getElementById('studentTab').classList.add('active');
        document.getElementById('facultyTab').classList.remove('active');
        document.getElementById('loginTypeTitle').textContent = 'Student Login';
        document.getElementById('loginType').value = 'student';
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>