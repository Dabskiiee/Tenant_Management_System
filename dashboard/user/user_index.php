<?php
require_once '../admin/authentication/admin-class.php';

$admin = new ADMIN();
if(!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

$stmt = $admin->runQuery("SELECT * FROM user_bills WHERE user_details = :id");
$stmt->execute(array(":id" => $_SESSION['userSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);


$stmt2 = $admin->runQuery("SELECT * FROM user WHERE id = :id");
$stmt2->execute(array(":id" => $_SESSION['userSession']));
$user_data2 = $stmt2->fetch(PDO::FETCH_ASSOC);

$total = $user_data['water'] + $user_data['rent'] + $user_data['electricity'] + $user_data['wifi'];
      
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
    <a href="#" class="active">Dashboard</a>
    <a href="#messages">User Profile</a>
    <a href="#settings">About Us</a>
    <a href="user_support.php">Support</a>
    <button class="sign-out"> 
        <a href="dashboard/admin/authentication/admin-class.php?admin-signout">SIGN OUT</a>
    </button>
</div>
    <div class="main-content">
    <div>
        <div class="user-info-container">
            <div class="user-info">
                <div class="profile-img">
                    <!-- Profile image placeholder -->
                </div>
                <div class="details">
                    <h3>Name: <span><?php echo $user_data2['fullname']; ?></span></h3>
                    <h3>Email: <span><?php echo $user_data['email']; ?></span></h3>
                    <h3>Balance: ₱<span><?php echo $user_data['balance']; ?></span></h3>
                </div>
            </div>
        </div>

         <!-- Bills Section -->
        <div class="bills">
            <div class="water">
                <div class="user-img" id="c_water">
                    <img src="../../src/img/watu.png" alt="poke" height="120vh"width ="140vw">
                </div>
                <div class="details">
                <h3>Water Bill</h3><br>
                <h3>₱<?php echo $user_data['water']; ?></h3>
                </div>
            </div>
            <div class="rent">
                <div class="user-img" id="c_rent">
                    <img src="../../src/img/haws.png" alt="rent" height="125vh"width ="100ch">
                </div>
                <div class="details">
                    <h3>Rent Bill</h3><br>
                    <h3>₱<?php echo $user_data['rent']; ?></h3>
                </div>
            </div>
            <div class="elec">
                <div class="user-img" id="c_elec">
                    <img src="../../src/img/elecc.png" alt="poke"  height="100vh"width ="100ch">
                </div>
                    <div class="details">
                        <h3>Electricity Bill</h3>
                        <h3>₱<?php echo $user_data['electricity']; ?></h3>
                    </div>
                </div>
                <div class="wifi" >
                    <div class="user-img" id="c_wifi">
                        <img src="../../src/img/wifi.png" alt="poke"  height="110vh"width ="100ch">
                    </div>
                    <div class="details">
                        <h3>Wifi Bill</h3>
                        <h3>₱<?php echo $user_data['wifi']; ?></h3>
                    </div>
                </div>  
        </div>
        <div class="total-amount">
                <h3>Total Amount Payable:₱<?php echo $total ?></h3>
        </div> 
</div>
</body>

</html>