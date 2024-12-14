<?php
require_once '../admin/authentication/admin-class.php';

$admin = new ADMIN();
if(!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['userSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/user/user_about.css">
    <title>Tenante || Information</title>
</head>
<body>

<div class="layout">
    <div class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="user_index.php" class="sidebar-link">Dashboard</a></li>
            <li><a href="user_history.php" class="sidebar-link">Mailbox</a></li>
            <li><a href="user_support.php" class="sidebar-link">Support</a></li>
            <li><a href="#" class="sidebar-link">About Us</a></li>
            <li><a href="user_profile.php" class="sidebar-link" >Profile</a></li>
        </ul>
    </div>
    <div class="main-content">
        <section class="about-us">
<div class="about">
            <img src="../../src/img/us.avif" class="pic" />
            <div class="text">
            <h2>About Us</h2>
                <p>Dear our valued tenants,</p>
                <p>Welcome to the Tenant Management System (TMS)! Our mission is to make your rental experience as seamless and convenient as possible. With TMS, managing your payments, submitting requests, and receiving important updates has never been easier. We’re here to ensure you have a hassle-free and efficient experience every step of the way.</p>
                <p>Our system is designed with your needs in mind, offering real-time tracking, automated reminders, and an intuitive interface, all aimed at making your rental journey hassle-free and efficient.</p>
                <br> 
                <br> 
                <p>FROM ITELEC GROUP 4 </p>
            </div>
        </div>
        </section>
    </div>  
</div>
</body>

</html>