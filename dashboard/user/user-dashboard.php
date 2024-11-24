<?php
require_once './dashboard/admin/authentication/admin-class.php';

$admin = new ADMIN();

if(!$admin->isUserLoggedIn()){
    $admin->redirect('../../');
}

$stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt->execute(array(":id" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/style.css">
    <title></title>
</head>
<body>
    <div>
        <div class="user-info-container">
            <div class="user-info">
                <div class="profile-img">
                    <!-- Profile image placeholder -->
                </div>
                <div class="details">
                    <h3>Name: <span><?php echo $user_data['fullname']; ?></span></h3>
                    <h3>Email: <span><?php echo $user_data['email']; ?></span></h3>
                </div>
            </div>
        </div>

         <!-- Bills Section -->
        <div class="bills">
            <div class="water">
                <h3>Water Bill</h3>
                <h4><?php echo $user_data['water_bill']; ?></h4>
            </div>
            <div class="rent">
                <h3>Rent Bill</h3>
                <h4><?php echo $user_data['rent_bill']; ?></h4>
            </div>
            <div class="elec">
                <h3>Electricity Bill</h3>
                <h4><?php echo $user_data['elec_bill']; ?></h4>
            </div>
            <div class="wifi">
                <h3>Wifi Bill</h3>
                <h4><?php echo $user_data['wifi_bill']; ?></h4>
            </div>
        </div>

        <!-- Total Amount Section -->
        <div class="total-amount">
            <h3>Total Amount Payable</h3>
            <h4>
                <?php 
                    $total = $user_data['water_bill'] + $user_data['rent_bill'] + $user_data['elec_bill'] + $user_data['wifi_bill'];
                    echo $total;
                ?>
            </h4>
        </div>
    </div>
</body>
</html>