<?php
require_once __DIR__.'/../../admin/authentication/admin-class.php';

CLASS User_Side{
    
    private $admin;

    public function __construct(){
        
        $this->admin = new ADMIN();
        
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

