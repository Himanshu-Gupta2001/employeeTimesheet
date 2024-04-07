<?php
session_start();
if (!isset($_SESSION['emp_username'])) {
    header("Location: index.php");
    exit();
}
include('config.php');

// Fetch employee's timesheets
$employee_id = $_SESSION['emp_id'];
$sql = "SELECT * FROM timesheets WHERE employee_id = '$employee_id'";
$result = mysqli_query($conn, $sql);

// Check if form is submitted for updating details or submitting new timesheet entry
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_details'])) {
        $timesheet_id = $_POST['timesheet_id'];
        $new_work_done = $_POST['new_work_done'];
        $new_feedback = $_POST['new_feedback'];

        // Check if the timesheet has been rated by the manager
        $check_rating_sql = "SELECT * FROM timesheets WHERE id = '$timesheet_id' AND rating IS NOT NULL";
        $rating_result = mysqli_query($conn, $check_rating_sql);
        if (mysqli_num_rows($rating_result) > 0) {
            echo "Cannot update details. Timesheet has already been rated by the manager.";
        } else {
            // Update details if not rated
            $update_sql = "UPDATE timesheets SET work_done = '$new_work_done', feedback = '$new_feedback' WHERE id = '$timesheet_id'";
            if (mysqli_query($conn, $update_sql)) {
                echo "Details updated successfully!";
                // Refresh page to reflect changes
                header("Refresh:0");
            } else {
                echo "Error updating details: " . mysqli_error($conn);
            }
        }
    } elseif (isset($_POST['submit_timesheet'])) {
        $work_done = $_POST['work_done'];
        $date = $_POST['date'];
        $feedback = $_POST['feedback'];

        // Insert new timesheet entry
        $insert_sql = "INSERT INTO timesheets (employee_id, work_done, date, feedback) VALUES ('$employee_id', '$work_done', '$date', '$feedback')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "Timesheet entry submitted successfully!";
            // Refresh page to reflect changes
            header("Refresh:0");
        } else {
            echo "Error submitting timesheet entry: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Timesheet</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
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

        form {
            margin-bottom: 20px;
        }

        textarea, input[type="date"], button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.8;
        }

        .timesheets {
            margin-top: 20px;
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
        <h2>Employee Timesheet</h2>
        <form action="" method="post">
            <textarea name="work_done" placeholder="Work Done" required></textarea>
            <input type="date" name="date" required>
            <textarea name="feedback" placeholder="Comment"></textarea>
            <button type="submit" name="submit_timesheet">Submit</button>
        </form>
        <div class="timesheets">
            <h3>Your Timesheets:</h3>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Work Done</th>
                    <th>Comment</th>
                    <th>Action</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['date']."</td>";
                    echo "<td>".$row['work_done']."</td>";
                    echo "<td>".$row['feedback']."</td>";
                    echo "<td>";
                    // Check if timesheet has been rated
                    if ($row['rating'] === null) {
                        echo "<form action='' method='post'>";
                        echo "<input type='hidden' name='timesheet_id' value='".$row['id']."'>";
                        echo "<input type='text' name='new_work_done' placeholder='New Work Done'>";
                        echo "<textarea name='new_feedback' placeholder='New Feedback'></textarea>";
                        echo "<button class='action-btn' type='submit' name='update_details'>Update</button>";
                        echo "</form>";
                    } else {
                        echo "<span>Timesheet rated by manager. Cannot edit details.</span>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
        <a href="logout.php" style="display: inline-block; background-color: red; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px;">Logout</a>
    </div>
</body>
</html>
