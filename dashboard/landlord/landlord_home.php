<?php
require_once '../admin/authentication/admin-class.php';

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

$id = $_SESSION['adminSession'];

$stmt = $admin->runQuery("SELECT fullname FROM user WHERE id = :id");      
$stmt->execute([':id' => $id]);

// Fetch the user data
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/landlord/landlord_home.css">
    <title>Home</title>
</head>
<body>

<div class="layout">


    <div class="main-content">

        <h3>Welcome Back <?= $user_data['fullname']?>!</h3>
        <h3 style="color:gray">What we gonna do now?</h3>
        
        <div class="button-container">
            <div class="button-item">
                <a href="landlord_tenant_profile.php" class="home-buttons" id="home-buttons-red">Tenant Profiles</a>
            </div>
            <div class="button-item">
                <a href="landlord_logs.php" class="home-buttons" id="home-buttons-blue">Logs Monitoring</a>
            </div>
            <div class="button-item">
                <a href="landlord_bill_mng.php" class="home-buttons" id="home-buttons-green">Bill Management</a>
            </div>
            <div class="button-item">
                <a href="landlord_comment.php" class="home-buttons" id="home-buttons-yellow">Comment Management</a>
            </div>
        </div>
        
    </div>
</div>
</body>

</html>