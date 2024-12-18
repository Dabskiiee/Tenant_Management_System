<?php
// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$database = "tenante_main"; // Replace with your database name

// Connect to the database
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Ensure 'room_no' is present
    if (isset($_POST['room_no']) && !empty($_POST['room_no'])) {
        $room_no = $_POST['room_no'];
        $elec = $_POST['elec'];
        $water = $_POST['water'];
        $rent = $_POST['rent'];
        $wifi = $_POST['wifi'];

        // Prepare and execute the update query
        $updateQuery = "UPDATE rent_distribution SET elec = ?, water = ?, rent = ?, wifi = ? WHERE room_no = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iiiis", $elec, $water, $rent, $wifi, $room_no);

        if ($stmt->execute()) {
            $message = "Bills for Room $room_no updated successfully!";
        } else {
            $message = "Error updating bills: " . $conn->error;
        }
    } else {
        $message = "Error: Room number is missing!";
    }
}

// Fetch all records from the table
$sql = "SELECT * FROM rent_distribution";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/landlord/landlord_side.css">
    <title>Update Rent Bills</title>
</head>
<body>

        <div class="layout">

        <div class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="landlord_home.php" class="sidebar-link">Home</a></li>
                <li><a href="landlord_tenant_profile.php" class="sidebar-link">Tenant Profiles</a></li>
                <li><a href="landlord_logs.php" class="sidebar-link">Logs Monitoring</a></li>
                <li><a href="#" class="sidebar-link">Bill Management</a></li>
                <li><a href="landlord_comment.php" class="sidebar-link">Comment Management</a></li>
                <a href="../admin/authentication/admin-class.php?admin_signout"><button type="button">Log
                        Out</button></a>
            </ul>
        </div>

        <div class="main-content">
    <h1 style="text-align: center;">Rent Bills</h1>
    <?php if (!empty($message)) { echo "<p style='text-align: center; color: green;'>$message</p>"; } ?>
    <table>
        <thead>
            <tr>
                <th>Room Number</th>
                <th>Total Electricity Bill</th>
                <th>Total Water Bill</th>
                <th>Total Rent Bill</th>
                <th>Total Wi-Fi Bill</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <form method='POST'>
                            <td>" . htmlspecialchars($row['room_no']) . "<input type='hidden' name='room_no' value='" . htmlspecialchars($row['room_no']) . "'></td>
                            <td><input type='number' name='elec' value='" . htmlspecialchars($row['elec']) . "' required></td>
                            <td><input type='number' name='water' value='" . htmlspecialchars($row['water']) . "' required></td>
                            <td><input type='number' name='rent' value='" . htmlspecialchars($row['rent']) . "' required></td>
                            <td><input type='number' name='wifi' value='" . htmlspecialchars($row['wifi']) . "' required></td>
                            <td><input type='submit' name='update' value='Update'></td>
                        </form>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    </div>
</body>
</html>
<?php
// Close the connection
$conn->close();
?>
