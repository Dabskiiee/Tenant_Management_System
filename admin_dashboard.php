<?php
require_once 'dashboard/admin/authentication/admin-class.php';

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
    <link rel="stylesheet" href="src/css/style.css">
    <title>ADMIN DASHBOARD</title>
</head>
<body>

    <?php include 'header.php'; ?>

<div class="layout">

    <?php include 'sidebar_nav.php'; ?>

    <div class="main-content">
        <?php include 'main.php'; ?>
    </div>
</div>
</body>

</html>

