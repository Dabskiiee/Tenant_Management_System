<?php
// Database connection
require_once '../admin/authentication/admin-class.php';
$admin = new ADMIN();
if(!$admin->isUserLoggedIn()) {
    $admin->redirect();
}
$stmt = $admin->runQuery("
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
");
$stmt->execute();
$roomData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Data</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
<form class="searchform" action="landlord_search_date.php" method="POST">
    <label for="datesearch">Search by Date:</label>
    <input id="date" type="text" name="datesearch" placeholder="Search for a specific date...">
    
    <label for="namesearch">Search by Name:</label>
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
            <?php foreach ($roomData as $room): ?>
                <tr>
                    <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($room['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($room['guests']); ?></td>
                    <td><?php echo htmlspecialchars($room['activity']); ?></td>
                    <td><?php echo htmlspecialchars($room['time_entered']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="landlord_home.php">Back</a>
</body>
</html>
