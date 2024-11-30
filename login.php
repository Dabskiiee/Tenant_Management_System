<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="src/css/login.css">
    <title>Tenant_Management_System Login Page</title>
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="dashboard/admin/authentication/admin-class.php" method="POST">
                <h1>Create Account</h1>
                <br>
                <span>or use your email for registration</span>
                <br>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="text" name="fullname" placeholder="Enter Fullname" required>
                <input type="email" name="email" placeholder="Enter Email" required> 
                <input type="password" name="password" placeholder="Enter Password" required>
                <button type="submit" name="btn-signup">Sign up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="dashboard/admin/authentication/admin-class.php" method="POST">
                <h1>Sign In</h1>
                <br>
                <span>or use your email and password</span>
                <br>
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token?>">
                <input type="email" name="email" placeholder="Enter Email" class="curve" required>
                <input type="password" name="password" placeholder="Enter Password" class="curve" required>
                <input type="number" name="guests" class="curve" placeholder="Guests"><br>
                <p class="recover">
                  <a href="forgot-password.php" class="forgot-pass">Forgot Password?</a>
                </p>
                <button type="submit" name="btn-signin">Sign in</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <script src="src/js/script.js"></script>
</body>

</html>
