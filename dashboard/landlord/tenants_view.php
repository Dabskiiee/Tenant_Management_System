<?php
require_once '../../dashboard/admin/authentication/admin-class.php';

$admin = new ADMIN();
if(!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user details based on the user ID
    $stmt = $admin->runQuery("SELECT * FROM user WHERE id = :id");
    $stmt->execute(array(":id" => $user_id));
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
        echo "User not found.";
        exit;
    }
} else {
    echo "No user ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/user/user_dashboard.css">
    <title>User Profile</title>
</head>
<body>

<div class="main-content">
    <h3>User Profile</h3>

    <div class="user-info-container">
        <div class="user-info">
            <div class="profile-image-section">
                <img src="<?php echo '../user/' . htmlspecialchars($user_data['profile_image'] ?? '../user/uploads/profile_pictures/default_profile.jpg'); ?>" alt="Profile Picture" class="profile-image">
            </div>
            <div class="details">
                <h3>Name: <span><?php echo $user_data['fullname']; ?></span></h3>
                <h3>Email: <span><?php echo $user_data['email']; ?></span></h3>
                <h3>First Name: <span><?php echo $user_data['firstname']; ?></span></h3>
                <h3>Last Name: <span><?php echo $user_data['lastname']; ?></span></h3>
                <h3>Birthday: <span><?php echo $user_data['birthday']; ?></span></h3>
                <h3>Gender: <span><?php echo $user_data['gender']; ?></span></h3>
                <h3>Civil Status: <span><?php echo $user_data['civil_status']; ?></span></h3>
            </div>
        </div>
    </div>
<button><a href="../landlord/landlord_tenant_profile.php">Back</a></button>
</div>

</body>
</html>
