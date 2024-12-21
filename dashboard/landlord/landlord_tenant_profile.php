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
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/landlord/tenants_profile.css ">
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
                    <a href="landlord_home.php" class="sidebar-link">
                        <i class="fa-solid fa-house"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="landlord_tenant_profile.php" class="sidebar-link">
                        <i class="fa-solid fa-user"></i>
                        <span>Tenant Profiles</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="landlord_logs.php" class="sidebar-link collapsed has-dropdown">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Logs Monitoring</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="landlord_bill_mng.php" class="sidebar-link collapsed has-dropdown">
                        <i class="fa-solid fa-receipt"></i>
                        <span>Bill Management</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="landlord_comment.php" class="sidebar-link collapsed has-dropdown">
                        <i class="lni lni-layout"></i>
                        <span>Comment Management</span>
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
                            <th>VIEW</th>
                            <th>REMOVE</th>
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
                                        <a href="tenants_view.php?id=<?= $row['id'] ?>" class="buttons view-btn"><i class="fas fa-eye"></i></a>
                                    </td>
                                    <td>
                                        <!-- REMOVE button inside the table row -->
                                        <form method="POST" action="" style="display:inline;">
                                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="remove_user" class="buttons remove-btn"><i class="fas fa-trash-alt"></i> </button>
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

            </main>
            <script>
        // Scroll to the search result after form submission (if any)
        <?php if ($search_term != ''): ?>
            var userRow = document.getElementById("user_<?= $row['id'] ?>");
            if (userRow) {
                userRow.scrollIntoView({
                    behavior: "smooth"
                });
            }
        <?php endif; ?>
    </script>


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


