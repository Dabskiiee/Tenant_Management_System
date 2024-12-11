<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();
if(!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

$stmt = $admin->runQuery("SELECT * FROM logs");
$stmt->execute();
$user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            <li><a href="#" class="sidebar-link">Logs</a></li>
            <li><a href="admin_comment.php" class="sidebar-link">Bulletin</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h3>TODAY'S LOGS</h3>

        <br>
        <br>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>GUESTS</th>
                    <th>ACTIVITY</th>
                    <th>TIME ENTERED</th>
                    
                </tr>
            </thead>
            <tbody>
                    <?php 
                    if($user_data){
                    foreach ($user_data as $row){
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
                    }else{
                    
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
            <button type="submit" name="admin-btn-save-log" class="buttons" id= "save">SAVE</button>
        </form>

    </div>
</div>
</body>

</html>