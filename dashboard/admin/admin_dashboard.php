<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

$stmt2 = $admin->runQuery('SELECT COUNT(id) AS no_of_rooms FROM rent_distribution'); // counts the number of tenants INSIDE the room
$stmt2->execute(); // No need for parameters here
$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

$number_of_rooms = $result2['no_of_rooms']; // Get the count of rooms from the result

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="../../src/css/admin/admin_dashboard.css">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="admin_dashboard.php">TENANTE MANAGEMENT</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="admin_dashboard.php" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Tenants</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="admin_logs.php" class="sidebar-link">
                        <i class="lni lni-agenda"></i>
                        <span>Logs</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="admin_comment.php" class="sidebar-link collapsed has-dropdown">
                        <i class="lni lni-layout"></i>
                        <span>Bulletin</span>
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

                <div class="layout">

                    <div class="table-responsive">
                        <br>
                        <h3>Tenants' BILLS AND DUES</h3>

                        <br>
                        <br>

                        <?php
                        // Loop through the rooms
                        for ($i = 1; $i <= $number_of_rooms; $i++) {
                            // Fetch user data for each room dynamically
                            $stmt = $admin->runQuery("SELECT * FROM user_bills WHERE room_no = :room_no");
                            $stmt->bindParam(':room_no', $i, PDO::PARAM_INT);
                            $stmt->execute();
                            $user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div>
                                <h1>ROOM <?= $i ?></h1>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>NAME</th>
                                            <th>EMAIL</th>
                                            <th>BALANCE</th>
                                            <th>ELECTRICITY</th>
                                            <th>WATER</th>
                                            <th>RENT</th>
                                            <th>WIFI</th>
                                            <th>UNPAID AMOUNT</th>
                                            <th>DUE DATE</th>
                                            <th>EDIT</th>
                                            <th>DELETE</th>
                                            <th>PAID</th>
                                            <th>UNSETTLED</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($user_data) {
                                            foreach ($user_data as $row) {
                                                ?>
                                                <tr>
                                                    <td><?= $row['name'] ?> </td>
                                                    <td><?= $row['email'] ?> </td>
                                                    <td><?= $row['balance'] ?> </td>
                                                    <td><?= $row['electricity'] ?> </td>
                                                    <td><?= $row['water'] ?> </td>
                                                    <td><?= $row['rent'] ?> </td>
                                                    <td><?= $row['wifi'] ?> </td>
                                                    <td><?= $row['unpaid_amt'] ?> </td>
                                                    <td><?= $row['due_date'] ?> </td>
                                                    <td><a class="buttons" id="edit"
                                                            href="edit.php?id=<?= $row['user_details'] ?>"><i
                                                                class="lni lni-pencil"></i></a></td>
                                                    <form action="" method="POST">
                                                        <td>
                                                            <button type="submit" name="admin-btn-delete" class="buttons"
                                                                id="delete" value="<?= $row['user_details'] ?>"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </td>
                                                        <td class="actions">
                                                            <button type="submit" name="admin-btn-paid" class="buttons" id="edit"
                                                                value="<?= $row['user_details'] ?>"><i
                                                                    class="paid-icon fas fa-check-circle"></i></button>
                                                        </td>
                                                        <td>
                                                            <a class="buttons" id="edit"
                                                                href="unsettle_payment.php?id=<?= $row['user_details'] ?>"><i
                                                                    class="unsettled-icon fas fa-exclamation-circle"></i></a>
                                                        </td>
                                                    </form>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="12">NO RECORD FOUND</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        }
                        ?>

                        <div>
                            <h1>ROOMS NEED TO BE SET</h1>
                            <table>
                                <thead>
                                    <tr>

                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>BALANCE</th>
                                        <th>ELECTRICITY</th>
                                        <th>WATER</th>
                                        <th>RENT</th>
                                        <th>WIFI</th>
                                        <th>DUE DATE</th>
                                        <th>EDIT</th>
                                        <th>DELETE</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $stmt = $admin->runQuery("SELECT * FROM user_bills WHERE room_no=0");
                                    $stmt->execute();
                                    $user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    if ($user_data) {
                                        foreach ($user_data as $row) { ?>
                                            <tr>

                                                <td><?= $row['name'] ?> </td>
                                                <td><?= $row['email'] ?> </td>
                                                <td><?= $row['balance'] ?> </td>
                                                <td><?= $row['electricity'] ?> </td>
                                                <td><?= $row['water'] ?> </td>
                                                <td><?= $row['rent'] ?> </td>
                                                <td><?= $row['wifi'] ?> </td>
                                                <td><?= $row['due_date'] ?> </td>
                                                <td><a class="buttons" id="edit"
                                                        href="edit.php?id=<?= $row['user_details'] ?>"><i
                                                            class="lni lni-pencil"></i></a></td>
                                                <td>
                                                    <form action="" method="POST">
                                                        <button type="submit" name="admin-btn-delete" class="buttons"
                                                            id="delete" value="<?= $row['user_details'] ?>"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                            </tr>

                                            <?php
                                        }
                                    } else {

                                        ?>
                                        <tr>
                                            <td colspan="11">NO RECORD FOUND</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </main>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="../../src/js/show.js"></script>
    <?php include_once '../../config/sweetalert.php'; ?>
</body>

</html>