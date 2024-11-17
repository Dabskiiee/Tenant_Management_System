<?php
session_start();

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="src/css/password.css">
    
</head>
<body>
    <div class ="con">
    <div class="container">
        <h2>Forgot Password</h2>
        <form method="POST" action="dashboard/admin/authentication/admin-class.php">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label for="email">Enter your registered email:</label>
            <input type="email" name="email" required>
            <button type="submit" name="btn-forgot-password">Send Reset Link</button>
        </form>
    </div>
    </div>
</body>
</html>
