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
$sql = "SELECT * FROM Patient WHERE PatientID = $patientID";
$result = mysqli_query($conn, $sql);

// Check if patient record exists
if (mysqli_num_rows($result) == 0) {
    header("Location: admin_dashboard.php");
    exit;
}

// Fetch patient data
$row = mysqli_fetch_assoc($result);

// Check if the form is submitted for updating patient details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contactNumber = $_POST['contactNumber'];
    $gender = $_POST['gender'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $bloodGroup = $_POST['bloodGroup'];
    $medicalHistory = $_POST['medicalHistory'];

    // Update patient details in the database
    $update_sql = "UPDATE Patient SET 
                    Name = '$name', 
                    Address = '$address', 
                    ContactNumber = '$contactNumber', 
                    Gender = '$gender', 
                    DateOfBirth = '$dateOfBirth', 
                    BloodGroup = '$bloodGroup', 
                    MedicalHistory = '$medicalHistory' 
                    WHERE PatientID = $patientID";
    $update_result = mysqli_query($conn, $update_sql);

    if ($update_result) {
        // Redirect to admin dashboard with success message
        header("Location: admin_dashboard.php?success=Patient details updated successfully");
        exit;
    } else {
        // Redirect to admin dashboard with error message
        header("Location: admin_dashboard.php?error=Failed to update patient details");
        exit;
    }
}
?>

<?php include 'include/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Edit Patient Details</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $patientID; ?>" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['Name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $row['Address']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="contactNumber">Contact Number</label>
                    <input type="text" class="form-control" id="contactNumber" name="contactNumber" value="<?php echo $row['ContactNumber']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="Male" <?php if($row['Gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if($row['Gender'] == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if($row['Gender'] == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dateOfBirth">Date of Birth</label>
                    <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" value="<?php echo $row['DateOfBirth']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="bloodGroup">Blood Group</label>
                    <input type="text" class="form-control" id="bloodGroup" name="bloodGroup" value="<?php echo $row['BloodGroup']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="medicalHistory">Medical History</label>
                    <textarea class="form-control" id="medicalHistory" name="medicalHistory" rows="3" required><?php echo $row['MedicalHistory']; ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
