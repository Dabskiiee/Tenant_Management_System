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

$total = $user_data['water'] + $user_data['rent'] + $user_data['electricity'] + $user_data['wifi'] + $user_data['unpaid_amt'];
      
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/user/user_dashboard.css">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="user_index.php">TENANTE MANAGEMENT</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="user_index.php" class="sidebar-link">
                        <i class="fa-solid fa-house"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="user_history.php" class="sidebar-link">
                    <i class="fa-solid fa-envelope"></i>
                        <span>Mailbox</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="user_support.php" class="sidebar-link collapsed has-dropdown">
                    <i class="fa-solid fa-handshake-angle"></i>
                        <span>Support Us</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="user_about_us.php" class="sidebar-link collapsed has-dropdown">
                    <i class="fa-solid fa-address-card"></i>
                        <span>About Us</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="user_profile.php" class="sidebar-link collapsed has-dropdown">
                    <i class="fa-solid fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
            </ul>
           
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-4 py-3">
                <form action="#" class="d-none d-sm-inline-block">

                </form>

            </nav>
            <main class="content px-3 py-4">
            <div>
        <div class="user-info-container">
            <div class="user-info">
                <div class="profile-image-section">
                    <img src="<?php echo htmlspecialchars($user_data2['profile_image'] ?? 'uploads/profile_pictures/default_profile.jpg'); ?>" alt="Profile Picture" class="profile-image">
                </div>
                <div class="details">
                    <h3>Name: <span><?php echo $user_data2['fullname']; ?></span></h3>
                    <h3>Email: <span><?php echo $user_data['email']; ?></span></h3>
                    <h3>Balance: ₱<span><?php echo $user_data['balance']; ?></span></h3>
                    <h3>Due date: <span><?php echo $user_data['due_date']; ?></span></h3>
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
                <h3>Total Amount Payable:₱<?php echo $total ?></h3><br>
                <h3 style="color:blue;">Add:<span style="color:tomato;text-decoration: underline;">₱<?=$user_data['unpaid_amt'] ?></span></h3>
        </div> 
            </main>
          
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="../../src/js/show.js"></script>
</body>

</html>
<?php
