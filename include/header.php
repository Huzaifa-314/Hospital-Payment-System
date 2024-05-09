
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Payment Gateway</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
                    <?php if(isset($_SESSION['UserID'])) { // If user is logged in ?>
                        <?php if($_SESSION['Role'] == 'Patient') { ?>
                            <a class="navbar-brand" href="index.php">Hospital Payment Gateway</a>
                        <?php } elseif($_SESSION['Role'] == 'Admin') { ?>
                            <a class="navbar-brand" href="admin_dashboard.php">Hospital Payment Gateway</a>
                        <?php } ?>
                    <?php } ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <?php if(isset($_SESSION['UserID'])) { // If user is logged in ?>
                        <?php if($_SESSION['Role'] == 'Patient') { ?>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="patient_details.php">Patient Details</a>
                            </li> -->
                        <?php } elseif($_SESSION['Role'] == 'Admin') { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin_dashboard.php">Patient List</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="bills.php">Bills</a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if(isset($_SESSION['UserID'])) { // If user is logged in ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
