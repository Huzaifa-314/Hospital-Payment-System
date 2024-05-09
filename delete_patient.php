<?php
session_start();

// Check if user is not logged in or not an admin, redirect to login.php
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] != 'Admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
include_once 'include/db_con.php';

// Check if patient ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

// Get the patient ID from the URL
$patientID = $_GET['id'];

// Check if the patient has any associated bills
$sql_check_bills = "SELECT * FROM Bill WHERE PatientID = $patientID";
$result_check_bills = mysqli_query($conn, $sql_check_bills);

if (mysqli_num_rows($result_check_bills) > 0) {
    // Patient has associated bills, redirect with error message
    header("Location: admin_dashboard.php?error=This patient has associated bills and cannot be deleted");
    exit;
}

// Delete patient record from the database
$delete_sql = "DELETE FROM Patient WHERE PatientID = $patientID";
$delete_result = mysqli_query($conn, $delete_sql);

if ($delete_result) {
    // Redirect to admin dashboard with success message
    header("Location: admin_dashboard.php?success=Patient record deleted successfully");
    exit;
} else {
    // Redirect to admin dashboard with error message
    header("Location: admin_dashboard.php?error=Failed to delete patient record");
    exit;
}
?>
