<?php

require 'database/dbconnection.php';

$user_id = '1'; // Fetch user ID from session

// Instantiate the Database class and connect
$db_instance = new Database();
$conn = $db_instance->dbConnection();

// Fetch user data
$sql = "SELECT * FROM user WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and capture form data
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $birthday = htmlspecialchars(trim($_POST['birthday']));
    $civil_status = htmlspecialchars(trim(strtolower($_POST['civil_status'])));
    $gender = htmlspecialchars(trim(strtolower($_POST['gender'])));
    $imagePath = $user['profile_image']; // Default to existing image

    // Handle image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/profile_pictures/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); // Create directory if not exists
        }

        $imageFileType = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            $newFileName = uniqid('profile_', true) . '.' . $imageFileType;
            $targetFile = $targetDir . $newFileName;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile; // Update the path
            } else {
                $error_message = "Failed to upload profile picture.";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }

    if (!isset($error_message)) {
        // Update user data including profile image
        $update_sql = "UPDATE user SET firstname = :firstname, lastname = :lastname, 
                       birthday = :birthday, civil_status = :civil_status, gender = :gender, 
                       profile_image = :profile_image WHERE id = :id";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(":firstname", $firstname, PDO::PARAM_STR);
        $update_stmt->bindParam(":lastname", $lastname, PDO::PARAM_STR);
        $update_stmt->bindParam(":birthday", $birthday, PDO::PARAM_STR);
        $update_stmt->bindParam(":civil_status", $civil_status, PDO::PARAM_STR);
        $update_stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
        $update_stmt->bindParam(":profile_image", $imagePath, PDO::PARAM_STR);
        $update_stmt->bindParam(":id", $user_id, PDO::PARAM_INT);

        if ($update_stmt->execute()) {
            $success_message = "Profile updated successfully!";
            header("Location: profile.php?success=1");
            exit;
        } else {
            $error_message = "Error updating profile.";
        }
    }
}

// Check for success flag in GET request
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_message = "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="src/css/profile.css">
</head>
<body>
    <div class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="sidebar-link">Dashboard</a></li>
            <li><a href="history.php" class="sidebar-link">History</a></li>
            <li><a href="profile.php" class="sidebar-link">Profile</a></li>
            <li><a href="message.php" class="sidebar-link">Messages</a></li>
            <li><a href="about.php" class="sidebar-link">About Us</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Profile</h1>
        <div class="notif">
        <?php if (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        </div>

        <form method="POST" action="" enctype="multipart/form-data" class="profile-form">
            <div class="profile-image-section">
                <img src="<?php echo htmlspecialchars($user['profile_image'] ?? 'default_profile.png'); ?>" alt="Profile Picture" class="profile-image">
                <label for="profile_image" class="profile-label">Profile Picture:</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="input-file">
            </div>

            <div class="profile-info-left">
                <label for="firstname" class="profile-label">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required class="input-text">

                <label for="lastname" class="profile-label">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required class="input-text">
            </div>

            <div class="profile-info-right">
                <label for="birthday" class="profile-label">Birthday:</label>
                <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>" required class="input-date">

                <label for="civil_status" class="profile-label">Civil Status:</label>
                <select id="civil_status" name="civil_status" required class="input-select">
                    <option value="single" <?php echo $user['civil_status'] == 'single' ? 'selected' : ''; ?>>Single</option>
                    <option value="married" <?php echo $user['civil_status'] == 'married' ? 'selected' : ''; ?>>Married</option>
                    <option value="divorced" <?php echo $user['civil_status'] == 'divorced' ? 'selected' : ''; ?>>Divorced</option>
                    <option value="widowed" <?php echo $user['civil_status'] == 'widowed' ? 'selected' : ''; ?>>Widowed</option>
                </select>

                <label for="gender" class="profile-label">Gender:</label>
                <select id="gender" name="gender" required class="input-select">
                    <option value="male" <?php echo $user['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo $user['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                    <option value="other" <?php echo $user['gender'] == 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Update Profile</button>
        </form>

        <br>
        <a href="login.php"><button type="button">Log Out</button></a>
        <a href="main_index.php"><button type="button">Back</button></a>
    </div>
</body>
</html>
