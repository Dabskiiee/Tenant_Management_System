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
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/user/user_about.css">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="landlord_tenant_profile.php">TENANTE MANAGEMENT</a>
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
            <div class="sidebar-footer">
                <a href="../admin/authentication/admin-class.php?admin_signout" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>

                </a>
            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-4 py-3">
                <form action="#" class="d-none d-sm-inline-block">

                </form>

            </nav>
            <main class="content px-3 py-4">
                   <section class="about-us">
        <div class="about">
            <img src="../../src/img/us.avif" class="pic" />
            <div class="text">
            <h2>About Us</h2>
                <p>Dear our valued tenants,</p>
                <p>Welcome to the Tenant Management System (TMS)! Our mission is to make your rental experience as seamless and convenient as possible. With TMS, managing your payments, submitting requests, and receiving important updates has never been easier. We’re here to ensure you have a hassle-free and efficient experience every step of the way.</p>
                <p>Our system is designed with your needs in mind, offering real-time tracking, automated reminders, and an intuitive interface, all aimed at making your rental journey hassle-free and efficient.</p>
                <br> 
                <br> 
                <p>FROM ITELEC GROUP 4 </p>
            </div>
        </div>
        </section>

               
            </main>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
                crossorigin="anonymous"></script>
            <script src="../../src/js/show.js"></script>
</body>

</html>
<?php
