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

if (isset($_POST['remove_user'])) {
    $user_id = $_POST['user_id'];

    // Delete the user from the database
    $stmt = $admin->runQuery("DELETE FROM user WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect back to the page to reflect changes
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<script>alert('Failed to remove the user. Please try again.');</script>";
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
                        // Create a unique anchor ID for each row
                        $anchor_id = "user_" . $row['id'];
                ?>
                    <tr id="<?= $anchor_id ?>">
                        <td><?= $row['id'] ?> </td>
                        <td><?= $row['fullname'] ?> </td>
                        <td><?= $row['email'] ?> </td>
                        <td>
                            <!-- VIEW button inside the table row -->
                            <a href="tenants_view.php?id=<?= $row['id'] ?>" class="buttons view-btn">VIEW</a>

                            <!-- REMOVE button inside the table row -->
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                <button type="submit" name="remove_user" class="buttons remove-btn">REMOVE</button>
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
// Scroll to the search result after form submission (if any)
<?php if ($search_term != ''): ?>
    var userRow = document.getElementById("user_<?= $row['id'] ?>");
    if (userRow) {
        userRow.scrollIntoView({ behavior: "smooth" });
    }
<?php endif; ?>
</script>

</body>
</html>
