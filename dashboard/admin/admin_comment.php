<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();
if(!$admin->isUserLoggedIn()) {
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/admin/admin_side.css">
    <title>ADMIN DASHBOARD</title>
</head>
<body>

<div class="layout">

    <div class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="admin_dashboard.php" class="sidebar-link">Tenants</a></li>
            <li><a href="admin_logs.php" class="sidebar-link">Logs</a></li>
            <li><a href="#" class="sidebar-link">Bulletin</a></li>
        </ul>
    </div>

    <div class="main-content">
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
</div>
</body>

</html>