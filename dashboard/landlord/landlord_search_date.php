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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/landlord/landlord_side.css">
    <title>Search Results</title>
</head>
<body>
<div class="layout">

    <div class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="landlord_home.php" class="sidebar-link">Home</a></li>
            <li><a href="landlord_tenant_profile.php" class="sidebar-link">Tenant Profiles</a></li>
            <li><a href="#" class="sidebar-link">Logs Monitoring</a></li>
            <li><a href="landlord_bill_mng.php" class="sidebar-link">Bill Management</a></li>
            <li><a href="landlord_comment.php" class="sidebar-link">Comment Management</a></li>
            <a href="../admin/authentication/admin-class.php?admin_signout"><button type="button">Log Out</button></a>
        </ul>
    </div>

    <div class="main-content">

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
    </div>
</body>
</html>
