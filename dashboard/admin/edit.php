<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();

if (isset($_GET['id'])) {

    $user_id = $_GET['id'];

    $stmt = $admin->runQuery("SELECT * FROM user_bills WHERE user_details = :user_id");
    $stmt->execute([":user_id" => $user_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tenant's Bills</title>
    <link rel="stylesheet" href="../../src/css/admin/edit.css">
</head>

<body>

    <form action="" method="POST">
        <a href="admin_dashboard.php"><img src="../../src/img/back_button.png" alt="haws" width="25px"
                height="30px"></a>
        <h1>EDIT USER BILLS AND DUE DATE</h1>


        <input type="hidden" name="id" value="<?= $user_data['user_details'] ?>">

        <div class="mb-3">
            <label>ROOM NUMBER</label>
            <input type="number" name="room_no" value="<?= $user_data['room_no'] ?>"><br>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="<?= $user_data['name'] ?>"><br>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="<?= $user_data['email'] ?>"><br>
        </div>

        <div class="mb-3">
            <label>Balance</label>
            <input type="number" name="balance" value="<?= $user_data['balance'] ?>"><br>
        </div>

        <div class="mb-3">
            <label>Electricity bill</label>
            <input type="number" name="electricity" value="<?= $user_data['electricity'] ?>"><br>
        </div>

        <div class="mb-3">
            <label>Water bill</label>
            <input type="number" name="water" value="<?= $user_data['water'] ?>"><br>
        </div>

        <div class="mb-3">
            <label>Rent bill</label>
            <input type="number" name="rent" value="<?= $user_data['rent'] ?>"><br>
        </div>

        <div class="mb-3">
            <label>Wifi bill</label>
            <input type="number" name="wifi" value="<?= $user_data['wifi'] ?>"><br>
        </div>

        <div class="mb-3">
            <label>Due date</label>
            <input type="date" name="due_date" value="<?= $user_data['due_date'] ?>"><br>
        </div>

        <button type="submit" name="admin-btn-save">SAVE</button>
    </form>
</body>

</html>