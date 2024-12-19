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
                    <th>PAID</th>
                    <th>UNSETTLED</th>
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
                            <td><a class="buttons" id= "edit" href="edit.php?id=<?=$row1['user_details']?>"><i class="lni lni-pencil"></i></a></td>
                            <form action="" method="POST">
                            <td>
                                <button type="submit" name="admin-btn-delete" class="buttons" id= "delete" value="<?=$row1['user_details']?>"><i class="fas fa-trash"></i></button>
                            </td>

                            <td class="actions">
                                <button type="submit" name="admin-btn-paid" class="buttons" id= "edit" value="<?=$row1['user_details']?>"><i class="paid-icon fas fa-check-circle"></i></button>
                            </td>
                            <td>
                                <a class="buttons" id= "edit" href="unsettle_payment.php?id=<?=$row1['user_details']?>"><i class="unsettled-icon fas fa-exclamation-circle"></i></a>
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
                    <th>PAID</th>
                    <th>UNSETTLED</th>
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
                            <td><?= $row2['unpaid_amt'] ?> </td>
                            <td><?= $row2['due_date'] ?> </td>
                            <td><a class="buttons" id= "edit" href="edit.php?id=<?=$row2['user_details']?>"><i class="lni lni-pencil"></i></a></td>
                            <form action="" method="POST">
                            <td>
                                <button type="submit" name="admin-btn-delete" class="buttons" id= "delete" value="<?=$row2['user_details']?>"><i class="fas fa-trash"></i></button>
                            </td>

                            <td class="actions">
                                <button type="submit" name="admin-btn-paid" class="buttons" id= "edit" value="<?=$row2['user_details']?>"><i class="paid-icon fas fa-check-circle"></i></button>
                            </td>
                            <td>
                                <a class="buttons" id= "edit" href="unsettle_payment.php?id=<?=$row2['user_details']?>"><i class="unsettled-icon fas fa-exclamation-circle"></i></a>
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
                    <th>PAID</th>
                    <th>UNSETTLED</th>
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
                            <td><a class="buttons" id= "edit" href="edit.php?id=<?=$row3['user_details']?>"><i class="lni lni-pencil"></i></a></td>
                            
                            <form action="" method="POST">
                            <td>
                                <button type="submit" name="admin-btn-delete" class="buttons" id= "delete" value="<?=$row3['user_details']?>"><i class="fas fa-trash"></i></button>
                            </td>

                            <td class="actions">
                                <button type="submit" name="admin-btn-paid" class="buttons" id= "edit" value="<?=$row3['user_details']?>"><i class="paid-icon fas fa-check-circle"></i></button>
                            </td>
                            <td>
                                <a class="buttons" id= "edit" href="unsettle_payment.php?id=<?=$row3['user_details']?>"><i class="unsettled-icon fas fa-exclamation-circle"></i></a>
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
                            <td><a class="buttons" id= "edit" href="edit.php?id=<?=$row['user_details']?>"><i class="lni lni-pencil"></i></a></td>
                            <td>
                            <form action="" method="POST">
                                <button type="submit" name="admin-btn-delete" class="buttons" id= "delete" value="<?=$row['user_details']?>"><i class="fas fa-trash"></i></button>
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
            </main>
          
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="../../src/js/show.js"></script>
    <?php include_once 'config/sweetalert.php'; ?>
</body>

</html>