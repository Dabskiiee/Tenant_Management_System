<?php
require_once '../admin/authentication/admin-class.php';

$admin = new ADMIN();
if(!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/landlord/landlord_side.css">
    <title>Home</title>
</head>
<body>

<div class="layout">

    <div class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="#" class="sidebar-link">Home</a></li>
            <li><a href="#" class="sidebar-link">Tenant Profiles</a></li>
            <li><a href="landlord_logs.php" class="sidebar-link">Logs Monitoring</a></li>
            <li><a href="#" class="sidebar-link">Bill Management</a></li>
            <li><a href="#">Comment Management</a></li>
            <li><a href="../admin/authentication/admin-class.php?admin_signout">Sign Out</a></li>
            
            <li><a href="landlord_tenant_profile.php" class="sidebar-link">Tenant Profiles</a></li>
            <li><a href="#" class="sidebar-link">Logs Monitoring</a></li>
            <li><a href="landlord_bill_mng.php" class="sidebar-link">Bill Management</a></li>
            <li><a href="landlord_comment.php" class="sidebar-link">Comment Management</a></li>
            <a href="../admin/authentication/admin-class.php?admin_signout"><button type="button">Log Out</button></a>

        </ul>
    </div>

    <div class="main-content">
    
    </div>
</div>
</body>

</html>