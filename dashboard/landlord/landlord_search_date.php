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
            l.time_entered,
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
            $query .= " AND DATE(l.time_entered) = :datesearch";
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
    <title>Search Results</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .no-results {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
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
                    <td><?php echo htmlspecialchars($result['time_entered']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="landlord_logs.php">Back</a>
</body>
</html>
