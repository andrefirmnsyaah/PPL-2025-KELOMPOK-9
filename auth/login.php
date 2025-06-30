<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi dan sanitasi input
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    if ($user === '' || $pass === '') {
        $error = "Username dan password harus diisi.";
    } else {
        // Gunakan prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare("SELECT username, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        // Periksa apakah user ditemukan
        if ($data = $result->fetch_assoc()) {
            // Verifikasi password
            if (password_verify($pass, $data['password'])) {
                $_SESSION['admin'] = $data['username'];
                header("Location: ../admin/index.php");
                exit;
            } else {
                $error = "Login gagal! Username atau password salah.";
            }
        } else {
            $error = "Login gagal! Username atau password salah.";
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <div class="login-clean">
        <form method="POST">
            <h2 class="sr-only">Login</h2>
            <div class="illustration"><i class="icon ion-lock-combination"></i></div>
            <div class="form-group"><input class="form-control" type="username" name="username" placeholder="Username" required></div>
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Log In</button></div>
            <?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>
