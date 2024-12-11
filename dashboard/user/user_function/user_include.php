<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenante || Information</title>
</head>
<body>
    <h1>SUPPORT</h1>

    <form action="user_function/user-side.php" method="POST">
    <br>
    <label for="to whom">To:</label>
    <select name="person" id="to_whom">
        <option value="admin">Admin</option>
        <option value="landlord">Landlord</option>
    </select>

    <br>
    <label for="kind">Type:</label>
    <select name="type" id="kind">
        <option value="Report">Report</option>
        <option value="Comment">Comment</option>
        <option value="Request">Request</option>
    </select>

    <br>
    <label for="message">Message:</label>
    <input type="text" name="message">
    
    <br>
    
    <button type="submit"name="btn-submit-sup">submit</button>
        
    </form>            
    
</body>
    
</html>