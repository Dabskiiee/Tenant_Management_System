<?php
// Database connection
require_once '../admin/authentication/admin-class.php';
$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect();
}
$stmt = $admin->runQuery("SELECT u.id AS room_number,
       CONCAT(u.firstname, ' ', u.lastname) AS user_name,
       l.activity,
       l.created_at,
       l.guests
FROM user u
JOIN logs l ON u.id = l.user_id
");
$stmt->execute();
$roomData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord logs</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/landlord/landlord_logs.css">
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
                <form class="searchform" action="landlord_search_date.php" method="POST">
                    <input id="date" type="text" name="datesearch" placeholder="Search for a specific date...">
                    <input id="namesearch" type="text" name="namesearch" placeholder="Search for a user name...">

                    <button type="submit">Search</button>
                </form>

                <h1>Room Data</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Name</th>
                            <th>Guests Entered</th>
                            <th>Activity</th>
                            <th>Time Entered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($roomData) {
                            foreach ($roomData as $room) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                                    <td><?php echo htmlspecialchars($room['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($room['guests']); ?></td>
                                    <td><?php echo htmlspecialchars($room['activity']); ?></td>
                                    <td><?php echo htmlspecialchars($room['created_at']); ?></td>
                                </tr>
                                <?php
                            }
                        } else { ?>
                            <tr>
                                <td colspan="5">NO RECORD FOUND</td>
                            </tr>
                        <?php } ?>

                        <?php ?>
                    </tbody>
                </table>
                <a href="landlord_home.php">Back</a>

            </main>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="../../src/js/show.js"></script>
</body>

</html>