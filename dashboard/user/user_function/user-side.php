<?php
require_once __DIR__.'/../../admin/authentication/admin-class.php';

CLASS User_Side{
    
    private $admin;

    public function __construct(){
        
        $this->admin = new ADMIN();
        
    }

    public function getUserData()
    {
        // Start session if it's not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Get user ID from session
        $user_id = $_SESSION['userSession'];

        // Fetch user data from the database
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->admin->runQuery($sql);
        $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user profile data
    public function updateUserProfile($postData, $fileData)
    {
        // Sanitize and capture form data
        $fullname = htmlspecialchars(trim($postData['fullname']));
        $firstname = htmlspecialchars(trim($postData['firstname']));
        $lastname = htmlspecialchars(trim($postData['lastname']));
        $birthday = htmlspecialchars(trim($postData['birthday']));
        $civil_status = htmlspecialchars(trim(strtolower($postData['civil_status'])));
        $gender = htmlspecialchars(trim(strtolower($postData['gender'])));
        $imagePath = $this->getUserData()['profile_image']; // Default to existing image

        // Handle image upload
        if (isset($fileData['profile_image']) && $fileData['profile_image']['error'] == UPLOAD_ERR_OK) {
            $targetDir = "uploads/profile_pictures/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true); // Create directory if not exists
            }

            $imageFileType = strtolower(pathinfo($fileData['profile_image']['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowedTypes)) {
                $newFileName = uniqid('profile_', true) . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;

                if (move_uploaded_file($fileData['profile_image']['tmp_name'], $targetFile)) {
                    $imagePath = $targetFile; // Update the path
                } else {
                    return "Failed to upload profile picture.";
                }
            } else {
                return "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            }
        }

        // Update user data including profile image
        $update_sql = "UPDATE user SET fullname = :fullname, firstname = :firstname, lastname = :lastname, 
                       birthday = :birthday, civil_status = :civil_status, gender = :gender, 
                       profile_image = :profile_image WHERE id = :id";

        $update_stmt = $this->admin->runQuery($update_sql);
        $update_stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
        $update_stmt->bindParam(":firstname", $firstname, PDO::PARAM_STR);
        $update_stmt->bindParam(":lastname", $lastname, PDO::PARAM_STR);
        $update_stmt->bindParam(":birthday", $birthday, PDO::PARAM_STR);
        $update_stmt->bindParam(":civil_status", $civil_status, PDO::PARAM_STR);
        $update_stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
        $update_stmt->bindParam(":profile_image", $imagePath, PDO::PARAM_STR);
        $update_stmt->bindParam(":id", $_SESSION['userSession'], PDO::PARAM_INT);

        if ($update_stmt->execute()) {
            return "Profile updated successfully!";
        } else {
            return "Error updating profile.";
        }
    }

    public function upload($role,$type,$text){

    $user_id = $_SESSION['userSession'];
    $address = $role;
    $typee = $type;
    $comment = $text;

    $this->comment($user_id,$address,$typee,$comment);
    
    echo "<script>alert('Thanks for improving our System!'); window.location.href = '../user_support.php'; </script>";
    
    }

    public function comment($user_id,$address,$typee,$comment){
    
    $stmt= $this->admin->runQuery('INSERT INTO user_comments(user_id,address,type,comment)VALUES(:user_id,:address, :type,:comment)');
    $stmt->execute([':user_id' =>$user_id , ':address'=>$address, ':type' =>$typee, ':comment' =>$comment]);
    }

    public function user_history($delete_mail){
    
        $stmt= $this->admin->runQuery('DELETE FROM user_notification WHERE id=:delete_mail');
        $stmt->execute([':delete_mail' =>$delete_mail]);
        echo "<script>alert('Mail deleted successfully!'); window.location.href = '../user_history.php'; </script>";
        }

    public function send_msg($receiver,$type,$text ){
        try {
            $stmt= $this->admin->runQuery('SELECT * FROM user WHERE fullname = :name');
            $stmt->execute([':name' => $receiver]);
            $result =$stmt->fetch(PDO::FETCH_ASSOC);
    
            if($result['usertype'] == 'admin'){
    
                $user_id = $_SESSION['adminSession'];
                $address =$receiver;      
                $typee = $type;    
                $comment = $text; 
    
                $this->comment($user_id,$address,$typee,$comment);
                
                echo "<script>alert('Admin is NOW informed.'); window.location.href = '../../landlord/landlord_comment.php';</script>";
            }elseif($result['usertype'] == 'user'){ 
    
                $user_id = $result['id'];
                $sent_by = $_SESSION['role'];
                $subject=$type;  
                $notif = "Subject:$subject 
                          Message:$text" ;  
    
                $this->admin->user_notification($user_id, $sent_by ,$notif);
    
                echo "<script>alert('User is notified!'); window.location.href = '../../landlord/landlord_comment.php';</script>";
            }else{
                echo "<script>alert('Your Message is not processed.'); window.location.href = 'landlord_message.php';</script>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    
}

if (isset($_POST['btn-submit-sup'])) {
    $role = trim($_POST['person']);
    $type =trim($_POST['type']);
    $text =trim($_POST['message']);

    $submit_com = new User_Side();
    $submit_com->upload($role,$type,$text);

}

if (isset($_POST['user-btn-delete'])) {
    $delete_mail = trim($_POST['user-btn-delete']);
    
    $delete_msg = new User_Side();
    $delete_msg->user_history($delete_mail);

}

if(isset($_POST['landlord-btn-send'])){
    $receiver = trim($_POST['person']);
    $type =trim($_POST['subject']);
    $text =trim($_POST['message']);

    $send_msgg= new User_Side();
    $send_msgg->send_msg($receiver,$type,$text);

}

