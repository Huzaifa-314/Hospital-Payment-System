<?php
session_start();

// Check if user is not logged in, redirect to login.php
if(!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
include_once 'include/db_con.php';

$error = (isset($_GET['error']))?$_GET['error']:'';
$success = (isset($_GET['success']))?$_GET['success']:'';

// Check if user has a patient record
$userID = $_SESSION['UserID'];
$sql_patient = "SELECT * FROM Patient WHERE UserID = $userID";
$result_patient = mysqli_query($conn, $sql_patient);

if(mysqli_num_rows($result_patient) == 0) {
    // User has no patient record, redirect to patient_details.php
    header("Location: patient_details.php");
    exit;
} else {
    // User has patient record, fetch patient details
    $patientDetails = mysqli_fetch_assoc($result_patient);
}

// Fetch bill information
$sql_bill = "SELECT * FROM Bill WHERE PatientID = {$patientDetails['PatientID']} and BillStatus='Unpaid'";
$result_bill = mysqli_query($conn, $sql_bill);
?>

<?php include 'include/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Patient Details</span>
                    <a href="update_patient.php" class="btn btn-sm btn-primary">Edit</a>
                </div>
                <div class="card-body">
                <?php if(!empty($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                        </div>
                    <?php } ?>
                    <?php if(!empty($success)) { ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success; ?>
                        </div>
                    <?php } ?>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Name:</strong> <?php echo $patientDetails['Name']; ?></li>
                        <li class="list-group-item"><strong>Address:</strong> <?php echo $patientDetails['Address']; ?></li>
                        <li class="list-group-item"><strong>Contact Number:</strong> <?php echo $patientDetails['ContactNumber']; ?></li>
                        <li class="list-group-item"><strong>Gender:</strong> <?php echo $patientDetails['Gender']; ?></li>
                        <li class="list-group-item"><strong>Date of Birth:</strong> <?php echo $patientDetails['DateOfBirth']; ?></li>
                        <li class="list-group-item"><strong>Blood Group:</strong> <?php echo $patientDetails['BloodGroup']; ?></li>
                        <li class="list-group-item"><strong>Medical History:</strong> <?php echo $patientDetails['MedicalHistory']; ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- Display bill information -->
            <?php if(mysqli_num_rows($result_bill) > 0) { ?>
                <div class="card">
                    <div class="card-header">Bill Information</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Issue Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result_bill)) { ?>
                                    <tr>
                                        <td><?php echo $row['BillAmount'].'/-'; ?></td>
                                        <td><?php echo $row['BillDate']; ?></td>
                                        <td>
                                            <a href="payment.php?price=<?php echo $row['BillAmount'];?>&BillID=<?php echo $row['BillID'];?>" class="btn btn-primary btn-block">Pay</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } else { ?>
                <div class="card">
                    <div class="card-header">Bill Information</div>
                    <div class="card-body">
                        <p>No bill information available.</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
