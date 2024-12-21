<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

$stmt = $admin->runQuery("SELECT * FROM logs");
$stmt->execute();
$user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Logs</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/admin/admin_logs.css">
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

                    <br>
                    <br>

                    <h3>TODAY'S LOGS</h3>

                    <br>
                    <br>

                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>GUESTS</th>
                                <th>ACTIVITY</th>
                                <th>TIME ENTERED</th>
                            </tr>
                        <tbody>
                            <?php
                            if ($user_data) {
                                foreach ($user_data as $row) {
                                    ?>
                                    <tr>
                                        <td><?= $row['user_id'] ?> </td>
                                        <td><?= $row['name'] ?> </td>
                                        <td><?= $row['guests'] ?> </td>
                                        <td><?= $row['activity'] ?> </td>
                                        <td><?= $row['created_at'] ?></td>
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

                    <form action="authentication/admin-class.php" method="POST">
                        <button type="submit" name="admin-btn-save-log" class="buttons" id="save">SAVE</button>
                    </form>

                </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
        <script src="../../src/js/show.js"></script>
        <?php include_once '../../config/sweetalert.php'; ?>
</body>

</html>