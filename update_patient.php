<?php
session_start();

// Check if user is not logged in, redirect to login.php
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
include_once 'include/db_con.php';

// Fetch current patient details
$userID = $_SESSION['UserID'];
$sql_patient = "SELECT * FROM Patient WHERE UserID = $userID";
$result_patient = mysqli_query($conn, $sql_patient);
$patientDetails = mysqli_fetch_assoc($result_patient);

// Check if form is submitted for updating patient details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contactNumber = $_POST['contact_number'];
    $gender = $_POST['gender'];
    $dateOfBirth = $_POST['date_of_birth'];
    $bloodGroup = $_POST['blood_group'];
    $medicalHistory = $_POST['medical_history'];

    // Update patient details in database
    $sql_update = "UPDATE Patient SET 
                    Name = '$name', 
                    Address = '$address', 
                    ContactNumber = '$contactNumber', 
                    Gender = '$gender', 
                    DateOfBirth = '$dateOfBirth', 
                    BloodGroup = '$bloodGroup', 
                    MedicalHistory = '$medicalHistory' 
                    WHERE UserID = $userID";

    if (mysqli_query($conn, $sql_update)) {
        // Redirect to index.php with success message
        header("Location: {$_SERVER['HTTP_REFERER']}?success=Patient record updated successfully");
        exit;
    } else {
        // Redirect to index.php with error message
        header("Location: {$_SERVER['HTTP_REFERER']}?error=Error in updating patient record");
        exit;
    }
}
?>

<?php include 'include/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Update Patient Details</div>
                <div class="card-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $patientDetails['Name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo $patientDetails['Address']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo $patientDetails['ContactNumber']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="Male" <?php if ($patientDetails['Gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                <option value="Female" <?php if ($patientDetails['Gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                <option value="Other" <?php if ($patientDetails['Gender'] == 'Other') echo 'selected'; ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo $patientDetails['DateOfBirth']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="blood_group">Blood Group</label>
                            <input type="text" class="form-control" id="blood_group" name="blood_group" value="<?php echo $patientDetails['BloodGroup']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="medical_history">Medical History</label>
                            <textarea class="form-control" id="medical_history" name="medical_history" rows="3"><?php echo $patientDetails['MedicalHistory']; ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
