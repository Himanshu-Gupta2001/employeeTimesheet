<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timesheet Application</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="half">
            <h2>Employee Login</h2>
            <form action="login.php" method="post">
                <input type="text" name="emp_username" placeholder="Username" required>
                <input type="password" name="emp_password" placeholder="Password" required>
                <button type="submit" name="emp_login">Login</button>
            </form>
        </div>
        <div class="half">
            <h2>Manager Login</h2>
            <form action="login.php" method="post">
                <input type="text" name="mgr_username" placeholder="Username" required>
                <input type="password" name="mgr_password" placeholder="Password" required>
                <button type="submit" name="mgr_login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
