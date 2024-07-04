<?php
session_start();
if ($_SESSION['role'] !== 'main_admin' && $_SESSION['role'] !== 'second_admin') {
    header("Location: index.php");
    exit();
}

require 'db.php';

//well defined vairble including role

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $role = isset($_POST['role']) ? trim($_POST['role']) : null;

    // Validate form data
    if ($username && $password && $role) {
        // Check if the second admin is trying to register a main admin
        if ($_SESSION['role'] == 'second_admin' && $role == 'main_admin') {
            echo "Second admin cannot register a main admin.";
            exit();
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $conn = get_db_connection();
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        header("Location: main_admin.php");
        exit();
    } else {
        echo "All fields are required.";
    }
}
?>
<!-- form -->
<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
    <link rel="stylesheet" href="register_first_user.css">
</head>
<body>
    <div class="form">
    <h2>Register User</h2>
    <form method="POST" action="">
        <div class="username">
            <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
    </div>
        <div class="password">
            <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
    </div>
        <div class="role"><label for="role">Role:</label><br>
        <!-- does not allow second admin to chose the main admin -->
        <select id="role" name="role">
            <?php if ($_SESSION['role'] == 'main_admin'): ?>
                <option value="main_admin">Main Admin</option>
            <?php endif; ?>
            <option value="second_admin">Second Admin</option>
            <option value="user">User</option>
        </select><br><br>
    </div>
        <div class="login">
            <input type="submit" value="Register">
    </div>
    </form>
</div>
</body>
</html>
