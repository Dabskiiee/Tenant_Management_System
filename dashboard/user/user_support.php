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
    <link rel="stylesheet" href="../../src/css/style.css">
    <title>Tenante || Information</title>
</head>
<body>

<?php include '../../header.php'; ?>
<div class="layout">
<div class="side-navbar">
    <a href="user_index.php">Dashboard</a>
    <a href="#messages">User Profile</a>

    <a href="user_about_us.php">About Us</a>
    <a href="user_support.php"class="active">Support</a>

    <a href="#settings">About Us</a>
    <a href="#"class="active">Support</a>

    <button class="sign-out"> 
        <a href="dashboard/admin/authentication/admin-class.php?admin-signout">SIGN OUT</a>
    </button>
</div>
    <div class="main-content">

         <?php include 'support.php'; ?>

         <?php include 'user_include.php'; ?>

    </div>  
</div>
</body>

</html>