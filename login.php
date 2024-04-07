<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['emp_login'])) {
        $username = $_POST['emp_username'];
        $password = $_POST['emp_password'];
        $sql = "SELECT * FROM employees WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['emp_id'] = $row['id'];
            $_SESSION['emp_username'] = $username;
            header("Location: employee_timesheet.php");
            exit();
        } else {
            echo "Invalid employee credentials";
        }
    } elseif (isset($_POST['mgr_login'])) {
        $username = $_POST['mgr_username'];
        $password = $_POST['mgr_password'];
        $sql = "SELECT * FROM managers WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            $_SESSION['mgr_username'] = $username;
            header("Location: manager_rating.php");
            exit();
        } else {
            echo "Invalid manager credentials";
        }
    }
}
?>
