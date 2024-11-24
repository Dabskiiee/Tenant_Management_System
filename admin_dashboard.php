<?php
require_once 'dashboard/admin/authentication/admin-class.php';

$admin = new ADMIN();
if(!$admin->isUserLoggedIn()) {
    $admin->redirect('../../');
}

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['userSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
</head>

<body>

    <h1>THIS IS ADMIN HOME PAGE <?php echo $user_data['fullname']; ?></h1>

    <a href="logout.php">Logout</a>
</body>

</html>