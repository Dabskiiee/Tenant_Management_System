<?php
require_once '../admin/authentication/admin-class.php';

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

$stmt = $admin->runQuery("SELECT * FROM user_comments WHERE address='landlord'");       //ONLY CALLS THE COMMENTS THAT ADDRESSES TO LANDLORD
$stmt->execute();
$user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/landlord/landlord_comment.css ">
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

            </main>
            <h3>COMMENTS</h3>

            <br>
            <br>

            <h2>TENANTS MESSAGES</h2>
            <table>
                <thead>
                    <tr>
                        <th>ROOM NUMBER</th>
                        <th>NAME</th>
                        <th>TYPE</th>
                        <th>MESSAGE</th>
                        <th>TIME SENT</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($user_data) {
                        foreach ($user_data as $row) {
                            $id = $row['user_id'];

                            $stmt = $admin->runQuery("SELECT usertype FROM user WHERE id=:id");     //CHECKS WHAT IS THE USERTYPE OF THE ID IN USERTYPE 
                            $stmt->execute([':id' => $id]);
                            $check = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($check['usertype'] === 'user') {          //CHECKS IF THE USERTYPE IS USER BEFORE EXECUTING
                                $stmt = $admin->runQuery("SELECT room_no , name FROM user_bills WHERE user_details=:id");     //For tenants
                                $stmt->execute([':id' => $id]);
                                $user_data1 = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>

                                <tr>
                                    <td><?= $user_data1['room_no'] ?> </td>
                                    <td><?= $user_data1['name'] ?> </td>
                                    <td><?= $row['type'] ?> </td>
                                    <td><?= $row['comment'] ?> </td>
                                    <td><?= $row['commented_at'] ?> </td>

                                    <form action="" method="POST">
                                        <td class="actions">
                                            <button type="submit" name="admin-btn-approve" class="buttons" id="edit"
                                                value="<?= $row['id'] ?>">APPROVE</button>
                                            <button type="submit" name="admin-btn-ignore" class="buttons" id="delete"
                                                value="<?= $row['id'] ?>">DECLINE</button>
                                        </td>
                                    </form>
                                </tr>

                        <?php
                            } elseif ($check['usertype'] === 'admin' || $check['usertype'] === 'landlord') {
                                //THE RESULT WILL NOT BE DISPLAYED
                            }
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6">NO RECORD FOUND</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <br>
            <br>
            <h2>Admin Replies</h2>
            <table>
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>TYPE</th>
                        <th>MESSAGE</th>
                        <th>TIME SENT</th>
                        <th>DELETE REPLY</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($user_data) {
                        foreach ($user_data as $row) {
                            $id = $row['user_id'];

                            $stmt = $admin->runQuery("SELECT usertype FROM user WHERE id=:id");     //CHECKS WHAT IS THE USERTYPE OF THE ID IN USERTYPE 
                            $stmt->execute([':id' => $id]);
                            $check = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($check['usertype'] === 'landlord') {          //CHECKS IF THE USERTYPE IS USER BEFORE EXECUTING

                    ?>

                                <tr>
                                    <td><?= htmlspecialchars('admin') ?> </td>
                                    <td><?= $row['type'] ?> </td>
                                    <td><?= $row['comment'] ?> </td>
                                    <td><?= $row['commented_at'] ?> </td>

                                    <form action="" method="POST">
                                        <td class="actions">
                                            <button type="submit" name="admin-btn-ignore" class="buttons" id="delete"
                                                value="<?= $row['id'] ?>">READ</button>
                                        </td>
                                    </form>

                                </tr>

                        <?php
                            } elseif ($check['usertype'] === 'admin' || $check['usertype'] === 'user') {
                                //THE RESULT WILL NOT BE DISPLAYED
                            }
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5">NO RECORD FOUND</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <a href="landlord_message.php">
                <button class="circle-compose-msg msg" type="button" title="Compose a MESSAGE">+</button>
            </a>



        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
        <script src="../../src/js/show.js"></script>
        <?php include_once 'config/sweetalert.php'; ?>
</body>

</html>
<?php
