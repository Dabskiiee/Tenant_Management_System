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
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/landlord/landlord_bill_mng.css">
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
            <h1 style="text-align: center;">Rent Bills</h1>
                <?php if (!empty($message)) {
                    echo "<p style='text-align: center; color: green;'>$message</p>";
                } ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="../../src/js/show.js"></script>
</body>

</html>
<?php
