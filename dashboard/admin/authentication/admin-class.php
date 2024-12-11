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
            
            $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email");
            $stmt->execute(array(":email" => $email));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            
            $user_details = $userRow['id'];
            $name = $userRow['fullname'];
            $email= $userRow['email'];
            $balance = 6500;
            $electricity = 0;
            $water = 0;
            $rent = 5000;
            $wifi = 1500;

            
            $datetime = new DateTime();


            $datetime->modify('first friday of next month'); // This moves to the first Friday of next month
            $datetime->modify('first friday of next month'); // This moves to the first Friday of the month after that

            $due_date = $datetime->format('Y-m-d');

            $this->user_bills($user_details,$name, $email,$balance,$electricity, $water, $rent, $wifi,$due_date);
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

                                $user_id = $userRow['id'];          //Stores user that will be put to every session based on usertype

                            if ($userRow['usertype'] === "admin") {     // Redirect based on usertype
                                $_SESSION['adminSession'] = $user_id;
                                echo "<script>alert('Welcome, Admin!'); window.location.href = '../admin_dashboard.php'; </script>";
                                
                            } elseif ($userRow['usertype'] === "user") {

                                $activity = "Has successfully signed in.";
                                $name = $userRow['fullname'];                                
                                $guests = isset($_POST['guests']) ? (int)$_POST['guests'] : 0;
                                $this->logs($user_id, $name, $guests, $activity);

                                $_SESSION['userSession'] = $user_id;    //ENSURES THAT ONLY THE USER WILL BE MONITORED TO THE LOGS TABLE

                                $stmt = $this->runQuery('SELECT due_date FROM user_bills WHERE email = :email');
                                $stmt->execute([':email' => $userRow['email']]);
                                $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

                                $due_date = $user_data['due_date']; 
                                $due_date_timestamp = strtotime($due_date); 

                                $cur_timestamp = time(); 
                                $alert_date = strtotime($due_date . ' -3 days');

                                if ($cur_timestamp >= $alert_date && $cur_timestamp < $alert_date + 86400) {
                                echo "<script>alert('3 days from now, Your rent will be on due!'); window.location.href = '../../user/user_index.php'; </script>";

                                $subject = "UPCOMING RENT ON DUE";
                                $message = "
                                <!DOCTYPE html>
                                <html>
                                <head>
                                    <meta charset='UTF-8'>
                                    <title>UPCOMING RENT DUE</title>
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
                                        <h1>Your Rent on due 3 days from now!</h1>
                                        <p>Hello Tenant!,</p>
                                        <p>So hi! I'm one of the moderators for Tenant! I like to inform you 3 days from now, you're rent is on due! </p>
                                        <p>Please prepare your payment thank you!</p>
                                        <p>Thank you!</p>
                                    </div>
                                </body>
                                </html>";

                                
                            $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);

                                
                                } elseif ($cur_timestamp >= $due_date_timestamp && $cur_timestamp < $due_date_timestamp + 86400) {
                                    echo "<script>alert('GO TO YOUR LANDLORD! Your rent payment is NOW DUE!'); window.location.href = '../../user/user_index.php'; </script>";

                                    $subject = "RENT DUE TODAY!!!!";
                                    $message = "
                                    <!DOCTYPE html>
                                    <html>
                                    <head>
                                        <meta charset='UTF-8'>
                                        <title>RENT DUE TODAY</title>
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
                                            <h1>Your Rent is DUE NOW!!</h1>
                                            <p>Hello Tenant!,</p>
                                            <p>So hi! I'm one of the moderators for Tenant! I like to inform you, your rent due is TODAY. THAT's ALL!</p>
                                            <p>Please prepare your payment thank you!</p>
                                            <p>Thank you!</p>
                                        </div>
                                    </body>
                                    </html>";
    
                                    
                                $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);

                                } else {
                                    echo "<script>window.location.href = '../../user/user_index.php';</script>";
                                }

                            }elseif ($userRow['usertype'] === "landlord"){
                                $_SESSION['adminSession'] = $user_id;
                                echo "<script>alert('WELCOME LANDLORD!'); window.location.href = '../../landlord/landlord_home.php'; </script>";
                                
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
        public function runQuery($sql)
        {
            $stmt = $this->conn->prepare($sql);
            return $stmt;

        }
        //GIVING VALUE TO THE FOREIGN TABLES WITH CONNECTION TO USER
        public function logs($user_id,$name, $guests, $activity)
        {
            $stmt = $this->runQuery("INSERT INTO logs (user_id,name,guests,activity) VALUES (:user_id,:name,:guests,:activity)");
            $stmt->execute(array(":user_id" => $user_id,":name" => $name ,":guests" => $guests ,":activity" => $activity ));
        }

        public function user_bills($user_details, $name, $email,$balance,$electricity, $water, $rent, $wifi,$due_date)
        {
            $stmt = $this->runQuery("INSERT INTO user_bills (user_details,name,email,balance,electricity, water,rent,wifi, due_date) VALUES (:user_details,:name,:email,:balance,:electricity,:water,:rent,:wifi,:due_date)");
            $stmt->execute(array(":user_details" => $user_details,":name"=>$name,":email" => $email,":balance" => $balance, ":electricity" => $electricity,":water" => $water,":rent" => $rent,":wifi" => $wifi,":due_date" => $due_date));
        }

        public function user_notification($user_id, $sent_by ,$notif)
        {
            $stmt = $this->runQuery("INSERT INTO user_notification (user_id, sent_by ,notif) VALUES (:user_id, :sent_by ,:notif)");
            $stmt->execute(array(':user_id' => $user_id,  ':sent_by' => $sent_by , ':notif' => $notif));
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

        public function forgotPassword($email, $csrf_token){ 
            
            if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {         // CSRF Token Validation
                echo "<script>alert('Invalid CSRF token.'); window.location.href = '../../../forgot-password.php'; </script>";
                exit;
            }
            unset($_SESSION['csrf_token']);

            
            $stmt = $this->runQuery("SELECT * FROM user WHERE email = :email");
            $stmt->execute(array(":email" => $email));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 1) {                                                       // Check if the email exists
                $userId = $userRow['id'];
                
                $token = bin2hex(random_bytes(32));                                 // Generate a secure reset token
                $tokenExpiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token valid for 1 hour

               
                $updateStmt = $this->runQuery("UPDATE user SET reset_token = :reset_token, token_expiry = :token_expiry WHERE email = :email");  // Store the token and its expiry in the database
                $updateStmt->execute(array(
                    ":reset_token" => $token,
                    ":token_expiry" => $tokenExpiry,
                    ":email" => $email
                ));

               
                $resetLink = "localhost/Tenant_Management_System/reset-password.php?token=" . $token . "&id=" . $userId;             // Prepare the reset link
                $resetLink = "localhost/Phps/Tenant_Management_System/reset-password.php?token=" . $token . "&id=" . $userId;
      
                $subject = "Password Reset Request";   // Email Subject and Body
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
            public function resetPassword($token, $new_password, $csrf_token){  //RESET PASSWORD
    
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
//ADMIN FUNCTIONS
public function update_user($room_no,$name,$email,$balance,$electricity,$water,$rent,$wifi,$due_date,$id){ //UPDATES THE USER_BILLS 

    $stmt= $this->runQuery('UPDATE user_bills SET room_no = :room_no , name=:name , email=:email , balance=:balance ,electricity=:electricity ,water=:water ,rent=:rent ,wifi=:wifi ,due_date=:due_date WHERE user_details = :id');
    $stmt->execute([':room_no' => $room_no, ':name'=> $name , ':email'=> $email , ':balance'=> $balance ,':electricity'=> $electricity ,':water'=> $water ,':rent'=> $rent ,':wifi'=> $wifi ,':due_date'=> $due_date , ':id' => $id]);
    echo "<script>alert('UPDATED SUCCESSFULLY!'); window.location.href = 'admin_dashboard.php'; </script>";
}



public function delete_user($deletee_user){ //DELETES THE USER WHEN IT DECIDED TO NO LONGER BE A TENANT

    try{
    $stmt= $this->runQuery('DELETE FROM user_bills WHERE user_details = :id');
    $stmt->execute([':id' => $deletee_user]);

    $stmt2= $this->runQuery('DELETE FROM user WHERE id = :id');
    $stmt2->execute([':id' => $deletee_user]);
    
    }catch(PDOException $e){
        echo $e->getMessage();
    }
    
}

public function save_log() { //SAVES THE LOG TABLE
    try {
        $stmt = $this->runQuery('SELECT * FROM logs');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

public function exportToFile($data) { //CREATES A TEXTFILE OUT OF THE SAVED LOG TABLE

    

    if ($data) {
        $cur_date = date("F-j-y");
        $filename = "logs ($cur_date).txt";
        $file = fopen($filename, "w");

        // Write column headers
        $headers = array_keys($data[0]);
        fputcsv($file, $headers, "\t");

        // Write rows
        foreach ($data as $row) {
            fputcsv($file, $row, "\t");
        }

        fclose($file);

        // Download the file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);

        
        unlink($filename);

    } else {
        echo "<script>alert('No data to export!'); window.location.href = '../admin_logs.php';</script>";
    }

    $stmt=$this->runQuery('DELETE FROM logs');
    $stmt->execute();
    echo "<script>alert('Text File Downloaded!'); window.location.href = '../admin_logs.php';</script>";

}

public function user_paid ($paid_user){

    try{
    $paid_id = $paid_user;

    $stmt= $this->runQuery('SELECT name,email FROM user_bills WHERE user_details=:paid_id');
    $stmt->execute([':paid_id' => $paid_id]);
    $result=$stmt->fetch(PDO::FETCH_ASSOC);

    if($result){
        $user_id= $paid_id;
        $month= date('F');
        $tenant=$result['name'];
        $email=$result['email'];

        $sent_by='landlord';
        $type='fully paid';
        $notif="Hey $tenant! You are recognized by the organization as $type for the month of $month. Keep being on time!";
        
        $this->user_notification($user_id, $sent_by ,$notif);

        $elec=0;
        $water=0;
        $unpaid_amt=0;
        $rent=5000;
        $wifi=1500;
        $datetime = new DateTime();
        $datetime->modify('first friday of next month'); // This moves to the first Friday of next month
        $due_date = $datetime->format('Y-m-d');

        $stmt=$this->runQuery('UPDATE user_bills SET electricity = :elec ,  water = :water ,  rent = :rent , wifi = :wifi , due_date = :due_date ,unpaid_amt = :unpaid_amt WHERE user_details=:id');
        $stmt->execute([':elec' =>$elec , ':water' =>$water,':rent' =>$rent , ':wifi' =>$wifi , ':due_date' => $due_date , ':unpaid_amt' => $unpaid_amt ,':id' => $user_id]);
    }    
        $subject = "FULLY PAID";
        $message = "
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset='UTF-8'>
                        <title>UPCOMING RENT DUE</title>
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
                        </style>
                        </head>
                        <body>
                                <div class='container'>
                                    <h1></h1>
                                    <p>Hey vro $tenant!,</p>
                                    <p>We got your payment and now you are fully paid for this month!</p>
                                    <p>Let's keep this kind of energy okay?</p>
                                    <p>Anyway, Thank you!</p>
                                    <br>
                                    <p>-$sent_by</p>
                                </div>
                            </body>
                            </html>";
                    $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);
                 
                    }catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                        return false;
                    }
                    echo "<script>alert('TENANT IS NOTIFIED ABOUT HIS PAYMENT. '); window.location.href = 'admin_dashboard.php';</script>";
    }

    public function unsettled_payment ($id,$unsettled_payment ){

        try{
        $unsettled_id = $id;
    
        $stmt= $this->runQuery('SELECT name,email FROM user_bills WHERE user_details=:unsettled_id');
        $stmt->execute([':unsettled_id' => $unsettled_id]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);

        if($result){
            $user_id= $unsettled_id;
            $month= date('F');
            $tenant=$result['name'];
        
            $sent_by='landlord';
            $type='unsettled amount';
            $notif="Hey $tenant! You have an $type for the month of $month. Please compromise to the next rent due!";
            
            $this->user_notification($user_id, $sent_by ,$notif);

            $elec=0;
            $water=0;
            $rent=5000;
            $wifi=1500;
            $datetime = new DateTime();
            $datetime->modify('first friday of next month'); // This moves to the first Friday of next month
            $due_date = $datetime->format('Y-m-d');
            $unpaid_amt = $unsettled_payment;

            $stmt=$this->runQuery('UPDATE user_bills SET electricity = :elec ,  water = :water ,  rent = :rent , wifi = :wifi , due_date = :due_date , unpaid_amt = :unpaid_amt WHERE user_details=:id');
            $stmt->execute([':elec' =>$elec , ':water' =>$water,':rent' =>$rent , ':wifi' =>$wifi , ':due_date' => $due_date, ':unpaid_amt' => $unpaid_amt , ':id' => $user_id]);
        }    
            $email=$result['email'];
            $subject = "UNSETTLED PAYMENT";
            $message = "
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset='UTF-8'>
                            <title>UNSETTLED PAYMENT</title>
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
                            </style>
                            </head>
                            <body>
                                    <div class='container'>
                                        <h1></h1>
                                        <p>Hey vro $tenant!,</p>
                                        <p>From our thorough collection of rents, You have an unsettled amount of ₱$unpaid_amt.</p>
                                        <p>Due to this, The unpaid amount will be added to your rent for next month</p>
                                        <p>Please compromise thanks!</p>
                                        <br>
                                        <p>-$sent_by</p>
                                    </div>
                                </body>
                                </html>";
                        $this->send_email($email, $message, $subject, $this->smtp_email, $this->smtp_password);
                     
                        }catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                            return false;
                        }
                        echo "<script>alert('TENANT IS NOTIFIED ABOUT HIS ACTIONS. '); window.location.href = 'admin_dashboard.php';</script>";
        } 

    
public function approve_user($approve_user) {  //NOTIFIES THE USER ABOUT HIS/HER CONCERN 
    try {
        
        $id=$approve_user;

        $stmt=$this->runQuery('SELECT user_id,address,type FROM user_comments WHERE id=:id');
        $stmt->execute([':id' => $id]);
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        
        if($result){
        $user_id=$result['user_id'];
        $sent_by=$result['address'];
        $type=$result['type'];
        $notif="Your $type is approved and noted by this dormitory! We will take further action as soon as possible!";
        
        $this->user_notification($user_id, $sent_by ,$notif);

        echo "<script>alert('APPROVAL SENT TO THE TENANT. '); window.location.href = 'admin_comment.php';</script>";

        $stmt=$this->runQuery('DELETE FROM user_comments WHERE id=:id');
        $stmt->execute([':id' => $id]);

    }else{
        echo "<script>alert('FAILED TO PROCESS the approval '); window.location.href = 'admin_comment.php';</script>";
    }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

public function ignore_user($ignore_user) {    //IGNORES THE USER'S CONCERN AND DELETE THE CONCERN BY THE ADMIN
    try {
        
        $id=$ignore_user;

        $stmt=$this->runQuery('DELETE FROM user_comments WHERE id=:id');
        $stmt->execute([':id' => $id]);
        
        echo "<script>alert('THE COMMENT IS IGNORED AND DELETED! '); window.location.href = 'admin_comment.php';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

public function distribute_rent($id) {    //IGNORES THE USER'S CONCERN AND DELETE THE CONCERN BY THE ADMIN

    try {
        
        $room_no=$id;

        $stmt = $this->runQuery('SELECT COUNT(room_no) AS tenants_per_room FROM user_bills WHERE room_no = :room_no;');
        $stmt->execute([':room_no' => $room_no]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) { // Check if there was a result
            $no_tenants = $result['tenants_per_room'];
        
            $stmt = $this->runQuery("SELECT * FROM rent_distribution");
            $stmt->execute();
            $rent_total = $stmt->fetch(PDO::FETCH_ASSOC);
        }
            if($no_tenants > 0){
                $total_elec = $rent_total['elec'];
                $total_water = $rent_total['water'];
                $total_rent = $rent_total['rent'];
                $total_wifi = $rent_total['wifi'];

                $distributed_elec = $total_elec/$no_tenants;
                $distributed_water = $total_water/$no_tenants;
                

                $stmt= $this->runQuery('UPDATE user_bills SET electricity=:electricity ,water=:water ,rent=:rent ,wifi=:wifi WHERE room_no = :room_no');
                $stmt->execute([':electricity'=> $distributed_elec ,':water'=> $distributed_water ,':rent'=> $total_rent,':wifi'=>  $total_wifi ,':room_no'=> $room_no]);

                echo "<script>alert('THERE ARE AT LEAST $no_tenants IN ROOM $room_no'); window.location.href = 'admin_comment.php';</script>";
            } else {

                echo "<script>alert('Error: No tenants in room $room_no'); window.location.href = 'admin_comment.php';</script>";   // If there are no tenants, show an alert
            }
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
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

if(isset($_POST['admin-btn-save'])){
    $id=trim($_POST['id']);
    $room_no=trim($_POST['room_no']);
    $name=trim($_POST['name']);
    $email=trim($_POST['email']);
    $balance=trim($_POST['balance']);
    $electricity=trim($_POST['electricity']);
    $water=trim($_POST['water']);
    $rent=trim($_POST['rent']);
    $wifi=trim($_POST['wifi']);
    $due_date=trim($_POST['due_date']);

    $update= new ADMIN();
    $update->update_user($room_no,$name,$email,$balance,$electricity,$water,$rent,$wifi,$due_date,$id);
}

if(isset($_POST['admin-btn-delete'])){
    $deletee_user=trim($_POST['admin-btn-delete']);

    $delete = new ADMIN();
    $delete->delete_user($deletee_user);
}

if (isset($_POST['admin-btn-save-log'])) {
    $exporter = new ADMIN();
    $data = $exporter->save_log();
    $exporter->exportToFile($data);
}

if (isset($_POST['admin-btn-approve'])) {
    $approve_user=trim($_POST['admin-btn-approve']);
    
    $approve_users=new ADMIN();
    $approve_users->approve_user($approve_user);
}

if (isset($_POST['admin-btn-ignore'])) {
    $ignore_user=trim($_POST['admin-btn-ignore']);

    $ignore_users=new ADMIN();
    $ignore_users->ignore_user($ignore_user);
}

if (isset($_POST['admin-btn-paid'])) {
    $paid_user=trim($_POST['admin-btn-paid']);

    $user_paid=new ADMIN();
    $user_paid->user_paid ($paid_user);
}

if(isset($_POST['admin-btn-unsettled'])){
    $id=trim($_POST['id']);
    $unsettled_payment=trim($_POST['unsettled_pay']);
    
    $unsettled_pay= new ADMIN();
    $unsettled_pay->unsettled_payment ($id,$unsettled_payment);
}
if(isset($_POST['admin-btn-distribute'])){
    $id=trim($_POST['admin-btn-distribute']);
    
    $distribute=new ADMIN();
    $distribute->distribute_rent($id);
}