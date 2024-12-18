<?php
require_once 'authentication/admin-class.php';

$admin = new ADMIN();
if(!$admin->isUserLoggedIn()) {
    $admin->redirect();
}

$stmt = $admin->runQuery("SELECT * FROM user_bills WHERE room_no=0");
$stmt->execute();
$user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $admin->runQuery("SELECT * FROM user_bills WHERE room_no=1");
$stmt->execute();
$user_data1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $admin->runQuery("SELECT * FROM user_bills WHERE room_no=2");
$stmt->execute();
$user_data2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $admin->runQuery("SELECT * FROM user_bills WHERE room_no=3");
$stmt->execute();
$user_data3 = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
            <li><a href="#" class="sidebar-link">Tenants</a></li>
            <li><a href="admin_logs.php" class="sidebar-link">Logs</a></li>
            <li><a href="admin_comment.php" class="sidebar-link">Bulletin</a></li>
            <a href="../admin/authentication/admin-class.php?admin_signout"><button type="button">Log Out</button></a>
        </ul>
    </div>

    <div class="main-content">
        <h3>Tenants' BILLS AND DUES</h3>

        <br>
        <br>

<div>
    <h1>ROOM 1</h1>
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
                    <th>REMARKS</th>
                </tr>
            </thead>
            <tbody>
                    <?php 
                    if($user_data1){
                    foreach ($user_data1 as $row1){?>
                        <tr>
                            
                            <td><?= $row1['name'] ?> </td>
                            <td><?= $row1['email'] ?> </td>
                            <td><?= $row1['balance'] ?> </td>
                            <td><?= $row1['electricity'] ?> </td>
                            <td><?= $row1['water'] ?> </td>
                            <td><?= $row1['rent'] ?> </td>
                            <td><?= $row1['wifi'] ?> </td>
                            <td><?= $row1['unpaid_amt'] ?> </td>
                            <td><?= $row1['due_date'] ?> </td>
                            <td><a class="buttons" id= "edit" href="edit.php?id=<?=$row1['user_details']?>">EDIT</a></td>
                            <form action="" method="POST">
                            <td>
                                <button type="submit" name="admin-btn-delete" class="buttons" id= "delete" value="<?=$row1['user_details']?>">DELETE</button>
                            </td>

                            <td class="actions">
                                <button type="submit" name="admin-btn-paid" class="buttons" id= "edit" value="<?=$row1['user_details']?>">PAID</button>
                                <a class="buttons" id= "edit" href="unsettle_payment.php?id=<?=$row1['user_details']?>">UNSETTLED</a>
                            </td>
                            </form>
                        </tr>

                    <?php
                    }
                    }else{
                    
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

<div>
    <h1>ROOM 2</h1>
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
                    <th>REMARKS</th>
                </tr>
            </thead>
            <tbody>
                    <?php 
                    if($user_data2){
                    foreach ($user_data2 as $row2){?>
                        <tr>
                            
                            <td><?= $row2['name'] ?> </td>
                            <td><?= $row2['email'] ?> </td>
                            <td><?= $row2['balance'] ?> </td>
                            <td><?= $row2['electricity'] ?> </td>
                            <td><?= $row2['water'] ?> </td>
                            <td><?= $row2['rent'] ?> </td>
                            <td><?= $row2['wifi'] ?> </td>
                            <td><?= $row1['unpaid_amt'] ?> </td>
                            <td><?= $row2['due_date'] ?> </td>
                            <td><a class="buttons" id= "edit" href="edit.php?id=<?=$row2['user_details']?>">EDIT</a></td>
                            <form action="" method="POST">
                            <td>
                                <button type="submit" name="admin-btn-delete" class="buttons" id= "delete" value="<?=$row2['user_details']?>">DELETE</button>
                            </td>

                            <td class="actions">
                                <button type="submit" name="admin-btn-paid" class="buttons" id= "edit" value="<?=$row2['user_details']?>">PAID</button>
                                <a class="buttons" id= "edit" href="unsettle_payment.php?id=<?=$row2['user_details']?>">UNSETTLED</a>
                            </td>
                            </form>
                        </tr>

                    <?php
                    }
                    }else{
                    
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

<div>
    <h1>ROOM 3</h1>
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
                    <th>REMARKS</th>
                </tr>
            </thead>
            <tbody>
                    <?php 
                    if($user_data3){
                    foreach ($user_data3 as $row3){?>
                        <tr>
                            
                            <td><?= $row3['name'] ?> </td>
                            <td><?= $row3['email'] ?> </td>
                            <td><?= $row3['balance'] ?> </td>
                            <td><?= $row3['electricity'] ?> </td>
                            <td><?= $row3['water'] ?> </td>
                            <td><?= $row3['rent'] ?> </td>
                            <td><?= $row3['wifi'] ?> </td>
                            <td><?= $row3['unpaid_amt'] ?> </td>
                            <td><?= $row3['due_date'] ?> </td>
                            <td><a class="buttons" id= "edit" href="edit.php?id=<?=$row3['user_details']?>">EDIT</a></td>
                            
                            <form action="" method="POST">
                            <td>
                                <button type="submit" name="admin-btn-delete" class="buttons" id= "delete" value="<?=$row3['user_details']?>">DELETE</button>
                            </td>

                            <td class="actions">
                                <button type="submit" name="admin-btn-paid" class="buttons" id= "edit" value="<?=$row3['user_details']?>">PAID</button>
                                <a class="buttons" id= "edit" href="unsettle_payment.php?id=<?=$row3['user_details']?>">UNSETTLED</a>
                            </td>
                            </form>
                            
                        </tr>

                    <?php
                    }
                    }else{
                    
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
                    if($user_data){
                    foreach ($user_data as $row){?>
                        <tr>
                            
                            <td><?= $row['name'] ?> </td>
                            <td><?= $row['email'] ?> </td>
                            <td><?= $row['balance'] ?> </td>
                            <td><?= $row['electricity'] ?> </td>
                            <td><?= $row['water'] ?> </td>
                            <td><?= $row['rent'] ?> </td>
                            <td><?= $row['wifi'] ?> </td>
                            <td><?= $row['due_date'] ?> </td>
                            <td><a class="buttons" id= "edit" href="edit.php?id=<?=$row['user_details']?>">EDIT</a></td>
                            <td>
                            <form action="" method="POST">
                                <button type="submit" name="admin-btn-delete" class="buttons" id= "delete" value="<?=$row['user_details']?>">DELETE</button>
                            </form>
                        </tr>

                    <?php
                    }
                    }else{
                    
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
</body>

</html>