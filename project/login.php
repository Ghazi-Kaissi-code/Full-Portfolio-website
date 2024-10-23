<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: darkgoldenrod;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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
        .form-container input {
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
    </style>
</head>
<body>
    <div class="form-container">
        <form method="POST" action="">
            <h2>Login</h2>
            <?php
            require_once('db.php');
            require_once('functions.php');
            session_start();
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $user = checkUser($pdo, $email, $password);
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    echo "Login successful!";
                    if ($user['role'] === 'admin') {
                        // Redirect to admin page
                        header("Location: home.php"); 
                    } else {
                        // Redirect to user page
                        header("Location: home.php"); 
                    }
                    exit();
                } else {
                    echo "Fail to register <br>";
                    echo "Check email or password <br>";
                }
            }
            ?>
         <body>
    <div class="form-container text-white">
        <form method="POST" action="">
            <h2>Login</h2>
            <input type="email" class="form-control" name="email" placeholder="Email" required>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <button type="submit" class="btn btn-primary mt-3">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Register here</a></p>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
