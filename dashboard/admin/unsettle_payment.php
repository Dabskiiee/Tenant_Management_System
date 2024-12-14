<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

if(isset($_GET['id'])) {

    $user_id=$_GET['id'];

$stmt = $admin->runQuery("SELECT user_details FROM user_bills WHERE user_details = :user_id");
$stmt->execute([":user_id" => $user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../src/css/admin/edit.css">
</head>
<body>
    
    <form action="" method="POST">
                <h1>UNSETTLED TENANT PAYMENT</h1>
                
                <input type="hidden" name="id" value="<?= $user_data['user_details'] ?>">
                
                    <div class="mb-3">
                        <label>Enter the "Kulang" :</label>
                        <input type="number" name="unsettled_pay"><br>
                    </div>

                    <button type="submit" name="admin-btn-unsettled">SAVE</button>
            </form>
</body>
</html>