<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();
if (!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

$stmt = $admin->runQuery("SELECT * FROM user_comments WHERE address = 'admin'");
$stmt->execute();
$user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $admin->runQuery("SELECT * FROM rent_distribution");
$stmt->execute();
$user_data1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/admin/admin_bulletin.css">
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
            <h3>Tenants' and Landlord's Bulletin</h3>

<br>
<br>


<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>TYPE</th>
            <th>MESSAGE</th>
            <th>TIME OF COMMENT</th>
            <th>APPROVE/IGNORE</th>
            
        
        </tr>
    </thead>
    <tbody>
            <?php 
            if($user_data){
            foreach ($user_data as $row){
            ?>
                <tr>
                    <td><?= $row['id'] ?> </td>
                    <td><?= $row['address'] ?> </td>
                    <td><?= $row['type'] ?> </td>
                    <td><?= $row['comment'] ?> </td>
                    <td><?= $row['commented_at'] ?> </td>
                    
                    <form action="" method="POST">
                    <td class="actions">
                        <button type="submit" name="admin-btn-approve" class="buttons" id= "edit" value="<?=$row['id']?>">APPROVE</button>
                        <button type="submit" name="admin-btn-ignore" class="buttons" id= "delete" value="<?=$row['id']?>">IGNORE</button>
                    </td>
                    </form>
                    
                </tr>

            <?php
            }
            }else{
            
            ?>
                <tr>
                    <td colspan="7">NO RECORD FOUND</td>
                </tr>
            <?php    
            }
            ?>
    </tbody>
</table>

<br>

<table>
    <thead>
        <tr>
            
            <th>ROOM</th>
            <th>ELECTRICITY BILL</th>
            <th>WATER BILL</th>
            <th>RENT BILL</th>
            <th>WIFI BILL</th>
            <th>DISTRIBUTE</th>
            
        </tr>
    </thead>
    <tbody>
            <?php 
            if($user_data1){
            foreach ($user_data1 as $row1){
            ?>
                <tr>
                    <td><?= $row1['id'] ?> </td>
                    <td><?= $row1['elec'] ?> </td>
                    <td><?= $row1['water'] ?> </td>
                    <td><?= $row1['rent'] ?> </td>
                    <td><?= $row1['wifi'] ?> </td>
                    
                    <form action="" method="POST">
                    <td class="actions">
                        <button type="submit" name="admin-btn-distribute" class="buttons" id= "edit" value="<?=$row1['room_no']?>">DISTRIBUTE</button>
                    </td>
                    </form>
                </tr>
            <?php
            }
            }else{
            ?>
                <tr>
                    <td colspan="7">NO RECORD FOUND</td>
                </tr>
            <?php    
            }
            ?>
    </tbody>
</table>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
        <script src="../../src/js/show.js"></script>
    </div>
    <?php include_once 'config/sweetalert.php'; ?>

</body>

</html>