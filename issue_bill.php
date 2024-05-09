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

// Fetch patient details based on patient ID
$sql_patient = "SELECT * FROM Patient WHERE PatientID = $patientID";
$result_patient = mysqli_query($conn, $sql_patient);

// Check if patient record exists
if (mysqli_num_rows($result_patient) == 0) {
    header("Location: admin_dashboard.php");
    exit;
}

// Fetch patient data
$row_patient = mysqli_fetch_assoc($result_patient);

// Check if form is submitted for issuing bill
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and collect form data
    $billAmount = $_POST['billAmount'];

    // Insert bill into the database
    $insert_sql = "INSERT INTO Bill (PatientID, BillAmount, BillStatus, BillDate) VALUES ($patientID, $billAmount, 'Unpaid', current_date());";
    $insert_result = mysqli_query($conn, $insert_sql);

    if ($insert_result) {
        // Redirect to admin dashboard with success message
        header("Location: admin_dashboard.php?success=Bill issued successfully");
        exit;
    } else {
        // Redirect to admin dashboard with error message
        header("Location: admin_dashboard.php?error=Failed to issue bill");
        exit;
    }
}
?>

<?php include 'include/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Issue Bill</h2>
            <div class="card mb-4">
                <div class="card-header">Patient Details</div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?php echo $row_patient['Name']; ?></p>
                    <p><strong>Address:</strong> <?php echo $row_patient['Address']; ?></p>
                    <p><strong>Contact Number:</strong> <?php echo $row_patient['ContactNumber']; ?></p>
                </div>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $patientID; ?>" method="post">
                <div class="form-group">
                    <label for="billAmount">Bill Amount</label>
                    <input type="number" class="form-control" id="billAmount" name="billAmount" required>
                </div>
                <button type="submit" class="btn btn-primary">Issue Bill</button>
            </form>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
