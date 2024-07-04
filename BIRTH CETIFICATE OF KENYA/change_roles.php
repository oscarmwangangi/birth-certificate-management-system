<?php
session_start();
require 'db.php';

// Check if the logged-in user is the main admin
if ($_SESSION['role'] !== 'main_admin') {
    header("Location: index.php");
    exit();
}

// Function to get all users from the database
function get_all_users() {
    $conn = get_db_connection();
    $stmt = $conn->query("SELECT id, username, role FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to update the user's role
function update_user_role($id, $new_role) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("UPDATE users SET role = :role WHERE id = :id");
    $stmt->execute(['role' => $new_role, 'id' => $id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user role
    $id = $_POST['user_id'];
    $new_role = $_POST['role'];
    update_user_role($id, $new_role);
    header("Location: change_roles.php"); // Refresh the page to see the changes
    exit();
}

$users = get_all_users();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Roles</title>
    <link rel="stylesheet" href="change_roles.css">
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to handle delete user button click
            $('.delete-user').click(function() {
                // Confirm the deletion
                if (confirm("Are you sure you want to remove this user from the table?")) {
                    // Remove the parent row of the delete button
                    $(this).closest('tr').remove();
                }
            });
        });
    </script>
<body>
     <div id="image">
            <a href="./index.php">
               <img src="./image/logout.jpeg" alt="" ></a>
          </div>
    <div class="form">
       
        
        <table><th><h2>Change User Roles</h2></th>
            <tr>
                
                <th>Username</th>
                <th>Current Role</th>
                <th>New Role</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <form method="POST" action="change_roles.php">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="role" required>
                            <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="main_admin" <?php echo $user['role'] == 'main_admin' ? 'selected' : ''; ?>>Main Admin</option>
                            <option value="second_admin" <?php echo $user['role'] == 'second_admin' ? 'selected' : ''; ?>>Second Admin</option>
                        </select>
                </td>
                <td>
                        <input type="submit" value="Update Role">
                        <button type="button" class="delete-user">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    
</body>
</html>
