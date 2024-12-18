<?php
require_once '../admin/authentication/admin-class.php';

$admin = new ADMIN();
if(!$admin->isUserLoggedIn()) {
    $admin->redirect();
}
$user_id=$_SESSION['userSession'];
$stmt = $admin->runQuery("SELECT * FROM user_notification WHERE user_id= :user_id");
$stmt->execute(array(":user_id" => $user_id));
$user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/user/user_history.css">
    <title>Tenante || Information</title>
</head>
<body >

<div class="layout">
<div class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="user_index.php" class="sidebar-link">Dashboard</a></li>
            <li><a href="#" class="sidebar-link">Mailbox</a></li>
            <li><a href="user_support.php" class="sidebar-link">Support</a></li>
            <li><a href="user_about_us.php" class="sidebar-link">About Us</a></li>
            <li><a href="user_profile.php" class="sidebar-link" >Profile</a></li>
        </ul>
</div>
    <div class="main-content" >

         <div class="header">
            <h3>NOTIFICATION</h3>
            <form action="user_function/user-side.php" method="POST">
            <button type="submit" name="user-btn-delete-all" value="<?=$user_id?>">DELETE ALL</button>
        </div>
        <hr>
    <?php 
        if($user_data){
        foreach($user_data as $row){?>
    
    <section class="message">
    <div class="message-bubble">
            
        <div class="sender">FROM: <?= $row['sent_by'] ?></div>
        <div class="notification"><?= nl2br($row['notif']) ?></div>
        <div class="time-sent"><?= $row['time_sent'] ?></div>

        <button type="submit" name="user-btn-delete" class="buttons" id= "edit" value="<?=$row['id']?>">DELETE</button>
        </form>
    </div>
    
</section>

    <?php  }}else{ ?>

    <section class="message">
    <div class="message-bubble">
        <div class="notification">YOUR MAIL BOX IS CLEAR</div>
    </div>
    
</section>

<?php }?>

</body>

</html>