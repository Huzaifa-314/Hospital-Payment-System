<?php
session_start();

// Check if user is not logged in or not an admin, redirect to login.php
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] != 'Admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
include_once 'include/db_con.php';

// Check if bill ID is provided
if (!isset($_GET['id'])) {
    header("Location: bills.php?error=Bill ID not provided");
    exit;
}

$billID = $_GET['id'];

// Check if bill has been paid
$sql_check_paid = "SELECT * FROM Bill WHERE BillID = $billID AND BillStatus = 'Paid'";
$result_check_paid = mysqli_query($conn, $sql_check_paid);

if (mysqli_num_rows($result_check_paid) > 0) {
    header("Location: bills.php?error=Cannot delete paid bill");
    exit;
}

// Delete bill
$sql_delete = "DELETE FROM Bill WHERE BillID = $billID";

if (mysqli_query($conn, $sql_delete)) {
    header("Location: bills.php?success=Bill deleted successfully");
    exit;
} else {
    header("Location: bills.php?error=Failed to delete bill");
    exit;
}
?>
