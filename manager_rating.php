<?php
session_start();
if (!isset($_SESSION['mgr_username'])) {
    header("Location: index.php");
    exit();
}
include('config.php');

// Fetch employees for the manager
$manager_username = $_SESSION['mgr_username'];
$sql = "SELECT e.id AS employee_id, e.username AS employee_username, t.work_done, t.date, t.feedback, t.rating
        FROM employees e
        LEFT JOIN timesheets t ON e.id = t.employee_id
        WHERE e.manager_id = (SELECT id FROM managers WHERE username = '$manager_username')";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Rating</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-btn {
            display: inline-block;
            margin-right: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            border-radius: 3px;
        }

        .action-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Employee Details</h2>
        <table>
            <tr>
                <th>Employee ID</th>
                <th>Username</th>
                <th>Work Done</th>
                <th>Date</th>
                <th>Comment</th>
                <th>Rating</th>
                <th>Action</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row['employee_id']."</td>";
                echo "<td>".$row['employee_username']."</td>";
                echo "<td>".$row['work_done']."</td>";
                echo "<td>".$row['date']."</td>";
                echo "<td>".$row['feedback']."</td>";
                echo "<td>".$row['rating']."</td>";
                echo "<td>";
                if ($row['rating'] !== null) {
                    echo "Already Rated";
                } else {
                    echo "<a href='rate_employee.php?employee_id=".$row['employee_id']."' class='action-btn'>Rate Employee</a>";
                }
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <a href="logout.php" style="display: inline-block; background-color: red; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px;">Logout</a>
    </div>
</body>
</html>
