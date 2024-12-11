<?php
require 'database/dbconnection.php';

// Instantiate the Database class and connect
$db_instance = new Database();
$conn = $db_instance->dbConnection();

// Assume we fetch the user with ID 1 for simplicity
$user_id = 1;

// Fetch user data
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input data
    $username = htmlspecialchars(trim($_POST['username']));
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $birthday = htmlspecialchars(trim($_POST['birthday']));
    $civil_status = htmlspecialchars(trim(strtolower($_POST['civil_status']))); // Ensure lowercase
    $gender = htmlspecialchars(trim(strtolower($_POST['gender']))); // Ensure lowercase

    // Update user data
    $update_sql = "UPDATE users SET username = :username, firstname = :firstname, lastname = :lastname, 
                   birthday = :birthday, civil_status = :civil_status, gender = :gender WHERE id = :id";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $update_stmt->bindParam(":firstname", $firstname, PDO::PARAM_STR);
    $update_stmt->bindParam(":lastname", $lastname, PDO::PARAM_STR);
    $update_stmt->bindParam(":birthday", $birthday, PDO::PARAM_STR);
    $update_stmt->bindParam(":civil_status", $civil_status, PDO::PARAM_STR);
    $update_stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
    $update_stmt->bindParam(":id", $user_id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        $success_message = "Profile updated successfully!";
        // Refresh user data to reflect changes
        header("Location: profile.php?success=1");
        exit;
    } else {
        $error_message = "Error updating profile.";
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
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="history.php">History</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="message.php">Messages</a></li>
            <li><a href="about.php">About Us</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>My Profile</h1>
        
        <?php if (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>

            <label for="firstname">First Name:</label><br>
            <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required><br><br>

            <label for="lastname">Last Name:</label><br>
            <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required><br><br>

            <label for="birthday">Birthday:</label><br>
            <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>" required><br><br>

            <label for="civil_status">Civil Status:</label><br>
            <select id="civil_status" name="civil_status" required>
                <option value="single" <?php echo $user['civil_status'] == 'single' ? 'selected' : ''; ?>>Single</option>
                <option value="married" <?php echo $user['civil_status'] == 'married' ? 'selected' : ''; ?>>Married</option>
                <option value="divorced" <?php echo $user['civil_status'] == 'divorced' ? 'selected' : ''; ?>>Divorced</option>
                <option value="widowed" <?php echo $user['civil_status'] == 'widowed' ? 'selected' : ''; ?>>Widowed</option>
            </select><br><br>

            <label for="gender">Gender:</label><br>
            <select id="gender" name="gender" required>
                <option value="male" <?php echo $user['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo $user['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                <option value="other" <?php echo $user['gender'] == 'other' ? 'selected' : ''; ?>>Other</option>
            </select><br><br>

            <button type="submit">Update Profile</button>
        </form>
        <br>
        <a href="login.php"><button type="button">Log Out</button></a>
        <a href="main_index.php"><button type="button">Back</button></a>
    </div>
</body>
</html>

