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

// Fetch bill details
$billID = $_GET['id'];
$sql_bill = "SELECT b.*, p.Name AS PatientName, p.Address AS PatientAddress FROM Bill b INNER JOIN Patient p ON b.PatientID = p.PatientID WHERE b.BillID = $billID";
$result_bill = mysqli_query($conn, $sql_bill);

if (mysqli_num_rows($result_bill) == 0) {
    header("Location: bills.php?error=Bill not found");
    exit;
}

$bill = mysqli_fetch_assoc($result_bill);

// Update bill details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    $sql_update = "UPDATE Bill SET BillAmount = '$amount', BillDate = '$date' WHERE BillID = $billID";

    if (mysqli_query($conn, $sql_update)) {
        header("Location: bills.php?success=Bill updated successfully");
        exit;
    } else {
        header("Location: edit_bill.php?id=$billID&error=Failed to update bill");
        exit;
    }
}
?>

<?php include 'include/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Edit Bill</h2>
            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_GET['error']; ?>
                </div>
            <?php } ?>
            <div class="card">
                <div class="card-header">
                    Patient Details
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?php echo $bill['PatientName']; ?></p>
                    <p><strong>Address:</strong> <?php echo $bill['PatientAddress']; ?></p>
                </div>
            </div>
            <form action="" method="post" class="mt-4">
                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="text" class="form-control" id="amount" name="amount" value="<?php echo $bill['BillAmount']; ?>">
                </div>
                <div class="form-group">
                    <label for="date">Issue Date:</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?php echo $bill['BillDate']; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
