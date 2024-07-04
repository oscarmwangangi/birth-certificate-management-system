<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}
require 'db.php';

$conn = get_db_connection();
$data = $conn->query("SELECT * FROM data WHERE user_id = {$_SESSION['user_id']}")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="user_dashboard.css">
</head>
<body>
<div class="form">
    <h2>User Dashboard</h2>
    
     <div class="Data Entry">
        <a href="./log/view.php">Data Entry</a>
    </div>
     
<div class="logout">
    <form method="POST" action="logout.php">
        <input type="submit" value="Logout">
    </form>
</div>
   
    <!--<ul>
        <?php foreach ($data as $row) : ?>
            <li><?php echo htmlspecialchars($row['content']); ?></li>
        <?php endforeach; ?>
    </ul> -->
</body>
</html>
