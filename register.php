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

// Check if form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if($password !== $confirmPassword) {
        $error = "Passwords do not match. Please try again.";
    } else {
        // Check if username or email already exists
        $sql = "SELECT * FROM User WHERE Username = '$username' OR Email = '$email'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0) {
            $error = "Username or Email already exists. Please try again.";
        } else {
            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into database
            $sql = "INSERT INTO User (Username, PasswordHash, Email, Role) VALUES ('$username', '$passwordHash', '$email', 'Patient')";
            if(mysqli_query($conn, $sql)) {
                // Registration successful, redirect to login.php with success message
                header("Location: login.php?success=Registration+successful.+You+can+now+login.");
                exit;
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
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
                <div class="card-header">Register</div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
