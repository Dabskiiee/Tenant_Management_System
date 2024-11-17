<?php
    include_once 'config/settings-configuration.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="src/css/login.css">

     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="src/js/script.js"></script>
</head>
<body>
   <div class="parent">
    <div id="login" class="wrapper active"> 
      <h1>Login</h1> <br>
      <form action="dashboard/admin/authentication/admin-class.php" method="POST">
          <input type="hidden" name="csrf_token" value="<?php echo $csrf_token?>">
          <input type="email" name="email" placeholder="Enter Email" class="curve" required><br>
          <input type="password" name="password" placeholder="Enter Password" class="curve" required><br>
          <p class="recover">
            <a href="forgot-password.php">Forgot Password?</a>
          <button type="submit" name="btn-signin">Sign in</button>
      </form>
    </div>

   <div class="parent">
    <div class="container">
        <div class="register">
            <h1>Sign up</h1> <br>
            <form action="dashboard/admin/authentication/admin-class.php" method ="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="text" name="fullname" placeholder="Enter Fullname" required><br>
                <input type="email" name="email" placeholder="Enter Email" required> <br>
                <input type="password" name="password" placeholder="Enter Password" required> <br>
                <button type="submit" name="btn-signup">Sign up</button>
            </form>
        </div>
    </div>
    </div>
   </div>

</body>
</html>