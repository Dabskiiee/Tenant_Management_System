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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/landlord/landlord_side.css">
    <title>Comment Management</title>
</head>

<body>

    <div class="layout">

        <div class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="landlord_home.php" class="sidebar-link">Home</a></li>
                <li><a href="landlord_tenant_profile.php" class="sidebar-link">Tenant Profiles</a></li>
                <li><a href="#" class="sidebar-link">Logs Monitoring</a></li>
                <li><a href="landlord_bill_mng.php" class="sidebar-link">Bill Management</a></li>
                <li><a href="#" class="sidebar-link">Comment Management</a></li>
                <a href="../admin/authentication/admin-class.php?admin_signout"><button type="button">Log
                        Out</button></a>
            </ul>
        </div>

        <div class="main-content">
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
    </div>
</body>

</html>