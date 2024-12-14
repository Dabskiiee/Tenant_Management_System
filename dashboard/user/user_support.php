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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/user/user_support.css">
    <title>Tenante || Information</title>
</head>
<body>


<div class="layout">
    <div class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="user_index.php" class="sidebar-link">Dashboard</a></li>
            <li><a href="user_history.php" class="sidebar-link">Mailbox</a></li>
            <li><a href="#" class="sidebar-link">Support</a></li>
            <li><a href="user_about_us.php" class="sidebar-link">About Us</a></li>
            <li><a href="user_profile.php" class="sidebar-link" >Profile</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="form-content">
            <h1>SUPPORT</h1>

            <form action="user_function/user-side.php" method="POST">
                <br>
                    <label for="to whom">To:</label>
                    <select name="person" id="to_whom">
                        <option value="Admin">Admin</option>
                        <option value="Landlord">Landlord</option>
                    </select>

                    <br>
                    <label for="kind">Type:</label>
                    <select name="type" id="kind">
                        <option value="Report">Report</option>
                        <option value="Comment">Comment</option>
                        <option value="Request">Request</option>
                    </select>

                    <br>
                    <label for="message">Message:</label>
                    <input type="text" name="message">

                <br>

                <button type="submit"name="btn-submit-sup">submit</button>
                    
            </form>
        </div>
    </div>  
</div>
</body>

</html>