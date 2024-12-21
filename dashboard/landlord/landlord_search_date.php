<?php
require_once '../admin/authentication/admin-class.php';
$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $dateSearch = !empty($_POST["datesearch"]) ? $_POST["datesearch"] : null;
    $nameSearch = !empty($_POST["namesearch"]) ? $_POST["namesearch"] : null;

    try {
        // Base query
        $query = "
        SELECT 
            u.id AS room_number,
            CONCAT(u.firstname, ' ', u.lastname) AS user_name,
            l.activity,
            l.created_at,
            l.guests
        FROM 
            user u
        JOIN 
            logs l
        ON 
            u.id = l.user_id
        WHERE 1=1";

        // Add date filter if provided
        if ($dateSearch) {
            $query .= " AND DATE(l.created_at) = :datesearch";
        }

        // Add name filter if provided
        if ($nameSearch) {
            $query .= " AND CONCAT(u.firstname, ' ', u.lastname) LIKE :namesearch";
        }

        // Prepare and execute the query
        $stmt = $admin->runQuery($query);

        // Bind parameters if provided
        if ($dateSearch) {
            $stmt->bindParam(":datesearch", $dateSearch);
        }
        if ($nameSearch) {
            $nameSearch = '%' . $nameSearch . '%'; // Allow partial matching
            $stmt->bindParam(":namesearch", $nameSearch);
        }

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
} else {
    header("Location: ../landlord_logs.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Search</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/landlord/landlord_search_date.css ">
</head>


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
            <h3>Search Results:</h3>

<?php if (empty($results)): ?>
    <div class="no-results">
        <p>No entries found for the specified date.</p>
    </div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Room Number</th>
                <th>User Name</th>
                <th>Guests Entered</th>
                <th>Activity</th>
                <th>Time Entered</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($result['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($result['guests']); ?></td>
                    <td><?php echo htmlspecialchars($result['activity']); ?></td>
                    <td><?php echo htmlspecialchars($result['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="landlord_logs.php">Back</a>

            </main>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
                crossorigin="anonymous"></script>
            <script src="../../src/js/show.js"></script>
        </div>
    </div>
    </body>

</html>