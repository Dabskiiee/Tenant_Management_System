<?php
    require_once __DIR__.'/../../../database/dbconnection.php';
    include_once __DIR__.'/../../../config/settings-configuration.php';
    require_once __DIR__.'/../../../src/vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class ADMIN
    {
        private $conn;
        private $settings;
        private $smtp_email;
        private $smtp_password;

        public function __construct()
        {
            $this->settings = new SystemConfig();
            $this->smtp_email = $this->settings->getSmtpEmail();
            $this->smtp_password = $this->settings->getSmtpPassword();

            $database = new Database();
            $this->conn = $database->dbConnection();
        }

        public function sendOtp($otp, $email){
            if($email == NULL){
                echo "<script>alert('No Email Found'); window.location.href = '../../../';</script>";
                exit;
            }else{
                $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email");
                $stmt->execute(array(":email" => $email));
                $stmt->fetch(PDO::FETCH_ASSOC);

                if($stmt->rowCount() > 0){
                    echo "<script>alert('Email is already taken. Please try another one.'); window.location.href = '../../../';</script>";
                    exit;
                }else{
                    $_SESSION['OTP'] = $otp;

                    $subject = "OTP VERIFICATION";
                    $message = "
                 <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <title>OTP Verification</title>
                    <style>
                        body{
                            font-family: Arial, sans-serif;
                            background-color: #f5f5f5;
                            margin: 0;
                            padding: 0;
                        }
                        
                        .container{
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 30px;
                            background-color: #ffffff;
                            border-radius: 4px;
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        }
                        
                        h1{
                            color: #333333;
                            font-size: 24px;
                            margin-bottom: 20px;
                        }

                        p{
                            color: #666666;
                            font-size: 16px;
                            margin-bottom: 10px;
                        }

                        .button{
                            display: inline-block;
                            padding: 12px 24px;
                            background-color: #0088cc;
                            color: #ffffff;
                            text-decoration: none;
                            border-radius: 4px;
                            font-size: 16px;
                            margin-top: 20px;
                        }

                        .logo{
                            display: block;
                            text-align: center;
                            margin-bottom: 30px;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='logo'>
                            <img src='cid:logo' alt='Logo' width='150'>
                        </div>
                        <h1>OTP Verification</h1>
                        <p>Hello, $email</p>
                        <p>Your OTP is, $otp</p>
                        <p>If you didn't request an OTP, please ignore this email.</p>
                        <p>Thank you!</p>
                    </div>
                </body>
                </html>";


                    $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);
                    echo "<script>alert('We sent the OTP to $email'); window.location.href = '../../../verify-otp.php';</script>";

                }
            }
        }

        public function verifyOTP($fullname, $email, $password, $tokencode, $otp, $csrf_token) {
            if($otp == $_SESSION['OTP']){
                unset($_SESSION['OTP']);
                
                $this->addAdmin($csrf_token, $fullname, $email, $password);

                $subject = "VERIFICATION SUCCESS";
                $message = "
             <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>OTP Verification SUCCESS</title>
                <style>
                    body{
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        margin: 0;
                        padding: 0;
                    }
                    
                    .container{
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 30px;
                        background-color: #ffffff;
                        border-radius: 4px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }
                    
                    h1{
                        color: #333333;
                        font-size: 24px;
                        margin-bottom: 20px;
                    }

                    p{
                        color: #666666;
                        font-size: 16px;
                        margin-bottom: 10px;
                    }

                    .button{
                        display: inline-block;
                        padding: 12px 24px;
                        background-color: #0088cc;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 4px;
                        font-size: 16px;
                        margin-top: 20px;
                    }

                    .logo{
                        display: block;
                        text-align: center;
                        margin-bottom: 30px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='logo'>
                        <img src='cid:logo' alt='Logo' width='150'>
                    </div>
                    <h1>Welcome</h1>
                    <p>Hello, <strong>$email</strong></p>
                    <p>Welcome to Adrian System</p>
                    <p>If you didn't sign up for an account, you can please ignore this email.</p>
                    <p>Thank you!</p>
                </div>
            </body>
            </html>";

            $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);
            echo "<script>alert('OTP Verified and Admin Added Successfully, Thank You 🙂'); window.location.href = '../../../index.php';</script>";

            unset($_SESSION['not_verify_fullname']);
            unset($_SESSION['not_verify_email']);
            unset($_SESSION['not_verify_password']);

         }else if($otp == NULL) {
            echo "<script>alert('No OTP Found'); window.location.href = '../../../verify-otp.php';</script>";
            exit;
         }else{
            echo "<script>alert('It appears that the OTP you entered is invalid'); window.location.href = '../../../verify-otp.php';</script>";
            exit;
         }
     }

        public function addAdmin($csrf_token, $fullname, $email, $password)
        {
            $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email");
            $stmt->execute(array(":email" => $email));

            if($stmt->rowCount() > 0){
                echo "<script>alert('Email already Exist.'); window.location.href = '../../../index.php';</script>";
                exit;
            }

            if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)){
                echo "<script>alert('Invalid CSRF Token.'); window.location.href = '../../../index.php';</script>";
                exit;
            }

            unset($_SESSION['csrf_token']);

            $hash_password = md5($password);

            $stmt = $this->runQuery('INSERT INTO user (fullname, email, password) VALUES (:fullname, :email, :password)');
            
            $exec = $stmt->execute(array(
                ":fullname" => $fullname,
                ":email" => $email,
                ":password" => $hash_password
            ));

        }

        public function adminSignin($email, $password, $csrf_token)
        {
            try {
                // CSRF Token Validation
                if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
                    echo "<script>alert('Invalid CSRF token.'); window.location.href = '../../../index.php'; </script>";
                    exit;
                }
                unset($_SESSION['csrf_token']);
        
                // Query to fetch user details
                $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email AND status = :status");
                $stmt->execute(array(":email" => $email, ":status" => "active"));
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($stmt->rowCount() == 1) {
                    // Check if user is active
                    if ($userRow['status'] == "active") {
                        // Validate password (consider using password_hash and password_verify for security)
                        if ($userRow['password'] == md5($password)) {
                            $activity = "Has successfully signed in.";
                            $user_id = $userRow['id'];
                            $guests = isset($_POST['guests']) ? (int)$_POST['guests'] : 0;
                            $this->logs($user_id, $guests, $activity );
                            // Store user in session
                            $_SESSION['userSession'] = $user_id;
        
                            // Redirect based on usertype
                            if ($userRow['usertype'] === "admin") {
                                echo "<script>alert('Welcome, Admin!'); window.location.href = '../../../admin_dashboard.php'; </script>";
                            } elseif ($userRow['usertype'] === "user") {
                                echo "<script>alert('Welcome, User!'); window.location.href = '../../../user_dashboard.php'; </script>";
                            } else {
                                echo "<script>alert('Invalid user type.'); window.location.href = '../../../index.php'; </script>";
                            }
                            exit;
                        } else {
                            echo "<script>alert('Password is incorrect.'); window.location.href = '../../../index.php'; </script>";
                            exit;
                        }
                    } else {
                        echo "<script>alert('Entered email is not verified.'); window.location.href = '../../../index.php'; </script>";
                        exit;
                    }
                } else {
                    echo "<script>alert('No account found for this email.'); window.location.href = '../../../index.php'; </script>";
                    exit;
                }
            } catch (PDOException $ex) {
                echo $ex->getMessage();
            }
        }
        
        

        public function adminSignout()
        {
            unset($_SESSION['adminSession']);
            echo "<script>alert('Sign Out Successfully'); window.location.href = '../../../index.php';</script>";
            exit;
        }

        function send_email($email, $message, $subject, $smtp_email, $smtp_password){
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "tls";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->addAddress($email);
            $mail->Username = $smtp_email;
            $mail->Password = $smtp_password;
            $mail->setFrom($smtp_email, "Adrian");
            $mail->Subject = $subject;
            $mail->msgHTML($message);
            $mail->Send();
        }

        public function logs($user_id, $guests, $activity)
        {
            $stmt = $this->runQuery("INSERT INTO logs (user_id,guests,activity) VALUES (:user_id,:guests,:activity)");
            $stmt->execute(array(":user_id" => $user_id, ":guests" => $guests ,":activity" => $activity ));
        }

        public function isUserLoggedIn()
        {
            if (isset($_SESSION['adminSession']) && $_SESSION['adminSession']) {
                return true;
            } elseif (isset($_SESSION['userSession']) && $_SESSION['userSession']) {
                return true;
            }
            return false;
        }
        

   
        public function redirect()
        {
            echo "<script>alert('Admin must loggin first'); window.location.href = '../../../index.php';</script>";
            exit;
        }

        public function runQuery($sql)
        {
            $stmt = $this->conn->prepare($sql);
            return $stmt;

        }

        public function forgotPassword($email, $csrf_token){
                // CSRF Token Validation
            if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
                echo "<script>alert('Invalid CSRF token.'); window.location.href = '../../../forgot-password.php'; </script>";
                exit;
            }
            unset($_SESSION['csrf_token']);


            // Check if the email exists
            $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email");
            $stmt->execute(array(":email" => $email));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 1) {
                $userId = $userRow['id'];
                // Generate a secure reset token
                $token = bin2hex(random_bytes(32));
                $tokenExpiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token valid for 1 hour

                // Store the token and its expiry in the database
                $updateStmt = $this->runQuery("UPDATE user SET reset_token = :reset_token, token_expiry = :token_expiry WHERE email = :email");
                $updateStmt->execute(array(
                    ":reset_token" => $token,
                    ":token_expiry" => $tokenExpiry,
                    ":email" => $email
                ));

                // Prepare the reset link
                $resetLink = "localhost/Tenant_Management_System/reset-password.php?token=" . $token . "&id=" . $userId;

                // Email Subject and Body
                $subject = "Password Reset Request";
                $message = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <title>Password Reset</title>
                    <style>
                        body{
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        margin: 0;
                        padding: 0;
                    }
                    
                    .container{
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 30px;
                        background-color: #ffffff;
                        border-radius: 4px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }
                    
                    h1{
                        color: #333333;
                        font-size: 24px;
                        margin-bottom: 20px;
                    }

                    p{
                        color: #666666;
                        font-size: 16px;
                        margin-bottom: 10px;
                    }

                    .button{
                        display: inline-block;
                        padding: 12px 24px;
                        background-color: #0088cc;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 4px;
                        font-size: 16px;
                        margin-top: 20px;
                    }

                    .logo{
                        display: block;
                        text-align: center;
                        margin-bottom: 30px;
                    }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <h1>Password Reset Request</h1>
                        <p>Hello,</p>
                        <p>You requested a password reset. Click the link below to reset your password:</p>
                        <p><a href='$resetLink'>Reset Password</a></p>
                        <p>If you did not request this, please ignore this email.</p>
                        <p>Thank you!</p>
                    </div>
                </body>
                </html>";

                // Send the reset email
                $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);

                echo "<script>alert('A password reset link has been sent to your email.'); window.location.href = '../../../index.php';</script>";
                exit;
                    } else {
                        echo "<script>alert('No account found with that email.'); window.location.href = '../../../forgot-password.php';</script>";
                        exit;
                    }
        }
            public function resetPassword($token, $new_password, $csrf_token){
    
        // CSRF Token Validation
        if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            echo "<script>alert('Invalid CSRF token.'); window.location.href = '../../../reset-password.php?token=$token'; </script>";
            exit;
        }
        unset($_SESSION['csrf_token']);


        // Retrieve user with the provided token
        $stmt = $this->runQuery("SELECT * FROM user WHERE reset_token = :reset_token AND token_expiry >= :current_time");
        $stmt->execute(array(
            ":reset_token" => $token,
            ":current_time" => date("Y-m-d H:i:s")
        ));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() == 1) {
            // Hash the new password
            $hash_password = md5($new_password);
            // Update the password and remove the reset token
            $updateStmt = $this->runQuery("UPDATE user SET password = :password, reset_token = NULL, token_expiry = NULL WHERE reset_token = :reset_token");
            $updateStmt->execute(array(
                ":password" => $hash_password,
                ":reset_token" => $token
            ));

            echo "<script>alert('Your password has been successfully reset. You can now log in with your new password.'); window.location.href = '../../../index.php';</script>";
            exit;
        } else {
            echo "<script>alert('Invalid or expired token. Please request a new password reset.'); window.location.href = '../../../forgot-password.php';</script>";
            exit;
        }
    }
        
    }   

    if(isset($_POST['btn-signup'])){
        $_SESSION['not_verify_fullname'] = trim($_POST['fullname']);
        $_SESSION['not_verify_email'] = trim($_POST['email']);
        $_SESSION['not_verify_password'] = trim($_POST['password']);

        $email = trim($_POST['email']);
        $otp = rand(100000, 999999);

        $addAdmin = new ADMIN();
        $addAdmin-> sendOtp($otp, $email);
    }

    if(isset($_POST['btn-verify'])){
        $csrf_token = trim($_POST['csrf_token']);
        $fullname =  $_SESSION['not_verify_fullname'];
        $email = $_SESSION['not_verify_email'];
        $password =  $_SESSION['not_verify_password'];

        $tokencode = md5(uniqid(rand()));
        $otp = trim($_POST['otp']);

        $adminVerify = new ADMIN();
        $adminVerify->verifyOTP($fullname, $email, $password, $tokencode, $otp, $csrf_token);

    }

    if(isset($_POST['btn-signin'])){
        $csrf_token = trim($_POST['csrf_token']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $admindSignin = new ADMIN();
        $admindSignin->adminSignin($email, $password, $csrf_token);
    }

    if(isset($_GET['admin_signout'])){
        $adminSignout = new ADMIN();
        $adminSignout->adminSignout();
    }

    if(isset($_POST['btn-forgot-password'])){
        $csrf_token = trim($_POST['csrf_token']);
        $email = trim($_POST['email']);
    
        $adminForgot = new ADMIN();
        $adminForgot->forgotPassword($email, $csrf_token);
    }

    if(isset($_POST['btn-reset-password'])){
        $csrf_token = trim($_POST['csrf_token']);
        $token = trim($_POST['token']);
        $new_password = trim($_POST['new_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_new_password = trim($_POST['confirm_new_password']);

    // Check if new_password and confirm_new_password match
    if ($new_password !== $confirm_new_password) {
        echo "<script>alert('Passwords do not match. Please try again.'); window.location.href = '../../../reset-password.php?token=$token';</script>";
        exit;
    }


        $adminReset = new ADMIN();
        $adminReset->resetPassword($token, $new_password, $csrf_token);
    }