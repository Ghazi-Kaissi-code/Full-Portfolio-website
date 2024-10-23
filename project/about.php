<?php
session_start();
require_once('db.php');
require_once('functions.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['message'] = "You must be logged in to access this page.";
    header("Location: login.php");
    exit();
}

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "You do not have admin rights to access this page.";
    header("Location: home.php");
    exit();
}

// Handle add action
if (isset($_POST['add_user'])) {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $role = htmlspecialchars($_POST['role']);
    if (addUser($pdo, $username, $email, $password, $role)) {
        $_SESSION['message'] = "User added successfully.";
    } else {
        $_SESSION['message'] = "Failed to add user.";
    }
    header("Location: about.php");
    exit();
}

// Handle edit action
if (isset($_POST['update_user'])) {
    $id = htmlspecialchars($_POST['id']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);
    if (editUser($pdo, $id, $username, $email, $role)) {
        $_SESSION['message'] = "User updated successfully.";
    } else {
        $_SESSION['message'] = "Failed to update user.";
    }
    header("Location: about.php");
    exit();
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = htmlspecialchars($_GET['delete']);
    if (deleteUser($pdo, $id)) {
        $_SESSION['message'] = "User deleted successfully.";
    } else {
        $_SESSION['message'] = "Failed to delete user.";
    }
    header("Location: about.php");
    exit();
}

// Fetch all users from the database
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Admin - User Management</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="about.php?edit=<?php echo $user['id']; ?>">Edit</a>
                            <a class="btn btn-danger btn-sm" href="about.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a class="btn btn-secondary" href="home.php">Back to Dashboard</a>
        </div>

        <!-- Add User Form -->
        <div class="mt-5">
            <h3 class="text-center">Add New User</h3>
            <form method="POST" action="about.php">
                <div class="mb-3">
                    <label for="newUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="newUsername" name="username" required>
                    </div>
                <div class="mb-3">
                    <label for="newEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="newEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="newPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="newPassword" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="newRole" class="form-label">Role</label>
                    <select class="form-select" id="newRole" name="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" name="add_user" class="btn btn-success">Add User</button>
            </form>
        </div>
    </div>

    <?php
    if (isset($_GET['edit'])) {
        $id = htmlspecialchars($_GET['edit']);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user):
    ?>
            <div class="modal show" tabindex="-1" style="display: block;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="about.php">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="update_user" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php
        endif;
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

