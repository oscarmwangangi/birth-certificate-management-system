<?php
session_start();
require 'db.php';

function is_first_user() {
    $conn = get_db_connection();
    $stmt = $conn->query("SELECT * FROM users");
    $user = $stmt->fetch();
    return $user === false;
}

$is_first_user = is_first_user();

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'main_admin':
            header("Location: main_admin.php");
            exit();
        case 'second_admin':
            header("Location: second_admin.php");
            exit();
        case 'user':
            header("Location: user_dashboard.php");
            exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $is_first_user ? 'Register' : 'Login'; ?> Page</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="form">
    <h2><?php echo $is_first_user ? 'Register' : 'Login'; ?></h2>
    
    <?php if ($is_first_user): ?>
        <form method="POST" action="register_first_user.php">
           <div class="username"> 
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required placeholder="username"><br><br>
        </div>
        <div class="password">
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required placeholder="password"><br><br>
        </div>
            <div class="login">
                <input type="submit" value="Register">
            </div>
        
        </form>
    <?php else: ?>
        <form method="POST" action="login.php">
          <div class="username">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required placeholder="username"><br><br>
        </div>
        <div class="password">
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required placeholder="password"><br><br>
        </div>
            <div class="login">
                <input type="submit" value="Login">
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
