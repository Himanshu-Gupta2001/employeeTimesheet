<?php
session_start();
if (!isset($_SESSION['mgr_username'])) {
    header("Location: index.php");
    exit();
}
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];
    // Fetch employee details
    $sql = "SELECT * FROM employees WHERE id = '$employee_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $employee = mysqli_fetch_assoc($result);
        // Display employee details
        echo "<h2>Rate Employee: ".$employee['username']."</h2>";
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='employee_id' value='".$employee_id."'>";
        echo "<label>Rating:</label>";
        echo "<select name='rating'>";
        echo "<option value='1'>1</option>";
        echo "<option value='2'>2</option>";
        echo "<option value='3'>3</option>";
        echo "<option value='4'>4</option>";
        echo "<option value='5'>5</option>";
        echo "</select>";
        echo "<button type='submit' name='submit_rating'>Submit Rating</button>";
        echo "</form>";
    } else {
        echo "Employee not found.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_rating'])) {
    $employee_id = $_POST['employee_id'];
    $rating = $_POST['rating'];
    // Update employee rating in the database
    $update_sql = "UPDATE timesheets SET rating = '$rating' WHERE employee_id = '$employee_id'";
    if (mysqli_query($conn, $update_sql)) {
        // Redirect back to manager_rating.php with success message
        header("Location: manager_rating.php?success=1");
        exit();
    } else {
        echo "Error rating employee: " . mysqli_error($conn);
    }
}
?>
