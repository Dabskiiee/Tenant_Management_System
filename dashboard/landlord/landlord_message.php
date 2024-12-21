<?php
require_once '../admin/authentication/admin-class.php';

$admin = new ADMIN();

$stmt = $admin->runQuery("SELECT * FROM user_bills");
$stmt->execute();
$user_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Message</title>
    <link rel="stylesheet" href="../../src/css/admin/edit.css">
</head>

<body>

    <form action="../user/user_function/user-side.php" method="POST">
        <a href="landlord_comment.php"><img src="../../src/img/back_button.png" alt="haws" width="25px"
                height="30px"></a>
        <h1>Message</h1>
        <div class="mb-3">
            <label class="beside" for="who">TO:</label>
            <select name="person" id="who">
                <option value="Admin">Admin</option>
                <?php
                if ($user_data) {
                    foreach ($user_data as $row) {
                        ?>
                        <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="beside">Subject:</label>
            <input id="shorter" type="text" name="subject"><br>
        </div>

        <div class="mb-3">
            <label>Message:</label>
            <input id="bigger" type="text" name="message" placeholder="Compose a Mail"><br>
        </div>

        <button type="submit" name="landlord-btn-send">SEND</button>
    </form>


</body>

</html>