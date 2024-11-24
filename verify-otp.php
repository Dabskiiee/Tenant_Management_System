<?php
    include_once 'config/settings-configuration.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/css/otp.css">
    <title>Verify OTP</title>
</head>
<body>
    <div class="con">
    <div class="container">
        <h2>Enter OTP</h2>
        <form action="dashboard/admin/authentication/admin-class.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="number" name="otp" placeholder="Enter OTP" required><br>
            <button type="submit" name="btn-verify">VERIFY</button>
        </form>
    </div>
    </div>
</body>
</html>