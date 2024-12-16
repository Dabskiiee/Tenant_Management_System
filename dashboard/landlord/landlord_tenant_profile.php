<?php
require_once '../../dashboard/admin/authentication/admin-class.php';

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

// Initialize the search term
$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : '';


// Modify the SQL query to filter users by username
if ($search_term != '') {
    $stmt = $admin->runQuery("SELECT * FROM user WHERE fullname LIKE :search_term");
    $stmt->execute([':search_term' => '%' . $search_term . '%']);
} else {
    $stmt = $admin->runQuery("SELECT * FROM user WHERE usertype = 'user'");
    $stmt->execute();
}

$user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['removeUserRow'])) {
    $user_id = $_POST['user_id'];

    // Fetch the current status of the user
    $stmt = $admin->runQuery("SELECT status FROM user WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Determine the new status
    if ($user['status'] == 'active') {
        $new_status = 'inactive'; // Change to inactive if currently active
    } else {
        $new_status = ''; // If not active, set status to empty
    }

    // Update the user's status
    $stmt = $admin->runQuery("UPDATE user SET status = :status WHERE id = :id");
    $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect to the same page to reflect changes
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<script>alert('Failed to update the user status. Please try again.');</script>";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/landlord/tenants_profile.css">
    <title>Tenant Profiles</title>
</head>
<body>

<div class="layout">

<div class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="landlord_home.php" class="sidebar-link">Home</a></li>
                <li><a href="#" class="sidebar-link">Tenant Profiles</a></li>
                <li><a href="landlord_logs.php" class="sidebar-link">Logs Monitoring</a></li>
                <li><a href="landlord_bill_mng.php" class="sidebar-link">Bill Management</a></li>
                <li><a href="landlord_comment.php" class="sidebar-link">Comment Management</a></li>

                <a href="../admin/authentication/admin-class.php?admin_signout"><button type="button">Log
                        Out</button></a>
            </ul>
        </div>
    <div class="main-content">
        <h3>TENANTS PROFILE</h3>

        <!-- Search Form -->
        <form method="POST" action="" class="search-form">
            <input type="text" name="search_term" placeholder="Search by username" value="<?= htmlspecialchars($search_term) ?>">
            <button type="submit" class="buttons search-btn">Search</button>
        </form>

        <!-- Back to Full List Button -->
        <?php if ($search_term != ''): ?>
            <form method="POST" action="" class="back-form">
                <button type="submit" class="buttons back-btn">Back to Full List</button>
            </form>
        <?php endif; ?>

        <br><br>

        <table>
        <thead>
    <tr>
        <th>ID</th>
        <th>USERNAME</th>
        <th>EMAIL</th>
        <th>ACTIONS</th>
    </tr>
</thead>
<tbody>
    <?php 
    if ($user_data) {
        foreach ($user_data as $row) {
            // Skip rows where the status is "inactive" or empty
            if (empty($row['status']) || $row['status'] === 'inactive') {
                continue;
            }

            // Create a unique anchor ID for each row
            $anchor_id = "user_" . $row['id'];
    ?>
        <tr id="<?= $anchor_id ?>">
            <td><?= $row['id'] ?> </td>
            <td><?= $row['fullname'] ?> </td>
            <td><?= $row['email'] ?> </td>
            <td style="text-align: center;">
                <!-- Both buttons in one cell -->
                <a href="tenants_view.php?id=<?= $row['id'] ?>" class="buttons view-btn">VIEW</a>
                <form method="POST" action="" style="display: inline;">
                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>" />
                    <button type="submit" name="removeUserRow" class="buttons remove-btn">REMOVE</button>
                </form>
            </td>
            </tr>
        <?php
            }
        } else {
        ?>
            <tr>
                <td colspan="5" style="text-align: center;">NO RECORD FOUND</td>
            </tr>
        <?php    
        }
        ?>
    </tbody>
</table>


    </div>
</div>

<script>
function removeUserRow(userId) {
    // Hide the user's row
    const userRow = document.getElementById(`user_${userId}`);
    if (userRow) {
        userRow.remove();
    }
}
</script>

</body>
</html>


