<?php
session_start();

// Check if user is not logged in, redirect to login.php
if(!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
include_once 'include/db_con.php';

// Check if user already has a patient record
$userID = $_SESSION['UserID'];
$sql = "SELECT * FROM Patient WHERE UserID = $userID";
$result = mysqli_query($conn, $sql);

// If user already has a patient record, redirect to index.php
if(mysqli_num_rows($result) > 0) {
    header("Location: index.php");
    exit;
}

// Initialize variables for error and success messages
$error = $success = '';

// Check if form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $contactNumber = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $bloodGroup = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $medicalHistory = mysqli_real_escape_string($conn, $_POST['medical_history']);

    // Insert patient details into database
    $sql_insert = "INSERT INTO Patient (UserID, Name, Address, ContactNumber, Gender, DateOfBirth, BloodGroup, MedicalHistory) 
                   VALUES ('$userID', '$name', '$address', '$contactNumber', '$gender', '$dob', '$bloodGroup', '$medicalHistory')";
    if(mysqli_query($conn, $sql_insert)) {
        // Patient details inserted successfully, redirect to index.php
        header("Location: index.php?success=Patient record updated successfully");
        exit;
    } else {
        // Error in inserting patient details
        // $error = "Error: " . mysqli_error($conn);
        header("Location: index.php?error=Error in updating patient record");
    }
}
?>

<?php include 'include/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if(!empty($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
            <div class="card">
                <div class="card-header">Patient Details</div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="name">Patient Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" required>
                        </div>
                        <div class="form-group">
                            <label for="blood_group">Blood Group</label>
                            <input type="text" class="form-control" id="blood_group" name="blood_group" required>
                        </div>
                        <div class="form-group">
                            <label for="medical_history">Medical History</label>
                            <textarea class="form-control" id="medical_history" name="medical_history" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
