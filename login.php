<?php
session_start();
// Redirect to index.php if user is already logged in
if(isset($_SESSION['UserID'])) {
    header("Location: index.php");
    exit;
}

// Include database connection
include_once 'include/db_con.php';

// Initialize variables for error and success messages
$error = $success = '';

// Catch success message from registration
if(isset($_GET['success'])) {
    $success = $_GET['success'];
}

// Check if form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve username and password from form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to fetch user details
    $sql = "SELECT * FROM User WHERE Username = '$username'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        // User found, verify password
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password, $row['PasswordHash'])) {
            // Password is correct, set session variables and redirect based on role
            $_SESSION['UserID'] = $row['UserID'];
            $_SESSION['Patient_name'] = $row['Username'];
            $_SESSION['Patient_email'] = $row['Email'];
            $_SESSION['Role'] = $row['Role'];
            if($row['Role'] == 'Admin') {
                // Redirect admin to admin_dashboard.php
                header("Location: admin_dashboard.php");
                exit;
            } else {
                // Redirect regular user to index.php
                header("Location: index.php?success=login+successful");
                exit;
            }
        } else {
            // Password is incorrect, show error
            $error = "Invalid password. Please try again.";
        }
    } else {
        // User not found, show error
        $error = "Username not found. Please try again.";
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
            <?php if(!empty($success)) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success; ?>
                </div>
            <?php } ?>
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                        <p class="mt-3">Not registered? <a href="register.php">Register here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
