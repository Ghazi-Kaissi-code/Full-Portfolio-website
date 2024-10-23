<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            
            background-color: darkgoldenrod;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container input,
        .form-container select {
            margin-bottom: 15px;
        }
        .form-container button {
            width: 100%;
            color: black;
            font-weight: bold;
            background-color: yellow;
        }
        .form-container button:hover {
            background-color: red;
        }
          
        #admin-pin {
            margin-top: 15px;
        }
    </style>
    <script>
        function togglePinField() {
            var role = document.getElementById("role").value;
            var pinField = document.getElementById("admin-pin");
            pinField.style.display = (role === "admin") ? "block" : "none";
        }
    </script>
</head>
<body>
    <div class="form-container">
        <form method="POST" action="">
            <h2>Register</h2>
            <?php
            require_once('db.php');
            require_once('functions.php');
            $username = $email = $password = $role = $pin = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $role = $_POST['role'] ?? 'user';
                $pin = $_POST['pin'] ?? ''; // Capture the PIN code

                // Validate PIN code if role is admin
                if ($role === 'admin' && $pin !== '159753') {
                    echo "Invalid admin PIN code!";
                } else {
                    $run = registerUser($pdo, $username, $email, $password, $role);
                    echo $run;
                }
            }
            ?>
            <div class="form-container text-white">
            <form method="POST" action="">
            <h2 class="text-center">Register</h2>
            <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required><br>
            <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required><br>
            <input type="password" class="form-control" name="password" placeholder="Password" value="<?php echo htmlspecialchars($password); ?>" required><br>
            <label>Select Role:</label>
            <select class="form-select" name="role" id="role" onchange="togglePinField()">
                <option value="user" <?php if ($role == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($role == 'admin') echo 'selected'; ?>>Admin</option>
            </select><br>
            <div id="admin-pin" style="display: none;">
                <label>Admin PIN:</label>
                <input type="text" class="form-control" name="pin" placeholder="Enter PIN"><br>
            </div>
            <button type="submit" class="btn  mt-3">Register</button>
        </form>
    </div>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>